<h2>Discrete model</h2>

<p>A discrete model interface is an extension of a filter interface. The SmartCGMS provides <a href="?page=entsignalgenerator">signal generator</a> filter, which instantiates selected discrete model and manages
its lifetime.
</p>

<p>
Discrete model is an entity, which has a state and is able to change this state in incremental, discrete steps. Unlike a <a href="?page=apisignalmodel">signal model</a>, a discrete model preserves its state, which
renders it unable to retrieve past signal values. A typical use of discrete model is to implement a compartment model - it has a state (current compartment quantities) and performs discrete steps (usually as
a single step of an ordinary differential equation solver).
</p>

<p>Descriptor of a discrete model contains <code>scgms::NModel_Flags::Discrete_Model</code> in the <code>flags</code> field. The model is instantiated using the <code>do_create_discrete_model</code> call.</p>

<h3>Interface</h3>

<p>
Every discrete model entity must implement both <code>scgms::IFilter</code> and <code>scgms::IDiscrete_Model</code> interface (as the former is a parent of the latter). The former is described in a
<a href="?page=apifilter">filter</a> subpage. The latter is defined as an abstract C++ class as follows:
</p>
<code><pre>
class IDiscrete_Model : public virtual scgms::IFilter {
public:
	virtual HRESULT IfaceCalling Initialize(const double current_time, const uint64_t segment_id) = 0;
	virtual HRESULT IfaceCalling Step(const double time_advance_delta) = 0;
};
</pre></code>

<p>
The <code>scgms::IFilter</code> method contracts remains the same - both are called upon the calls to the parent filter (i.e.; when the signal generator filter interface method is called,
the filter calls the same method of the discrete model).
</p>

<p>
The <code>Initialize</code> is called by outer code typically at the start of a time segment. Its sole purpose is to initialize the state of the discrete model at a given time. The model should store
the timestamp, as any further stepping is performed in relative increments. This method must be called exactly once, before any <code>Step</code> method call. Any further calls results in
<code>E_ILLEGAL_STATE_CHANGE</code> return code. Otherwise, this method returns <code>S_OK</code>.
</p>

<p>
The <code>Step</code> method is called for two reasons. The first is to advance the model internal state by a fixed, discrete amount of time, when the <code>time_advance_delta</code> parameter is a positive number.
The second is to emit current state, when the <code>time_advance_delta</code> equals zero. This parameter must never be negative, othwerise the call returns with <code>E_INVALIDARG</code> error code. This method
should also never be called prior the <code>Initialize</code> call. Doing so will result in an <code>E_ILLEGAL_METHOD_CALL</code> error code.
</p>
<!-- rozsireni filter iface -->

<h3>Operation</h3>

<p>
The discrete model extends the filter state model :
<ul>
    <li><i>Created</i> - the discrete model just got instantiated and waits for the <code>Configure</code> call.</li>
    <li><i>Configured</i> - the discrete model is initialized and waits for the <code>Initialize</code> method call.</li>
    <li><i>Operational</i> - the discrete model is configured and properly initialized. It is able to process device events in <code>Execute</code> method and peform steps in <code>Step</code> method. The <code>Step</code> method is called exclusively in this state.</li>
    <li><i>Terminated</i> - the discrete model received the <code>Shut_Down</code> device event code, deallocated all its resources and waits for its deallocation by outer code. Any further <code>Configure</code>, <code>Execute</code>, <code>Initialize</code> and <code>Step</code> call on this discrete model instance is invalid.</li>
</ul>
The figure below illustrates the discrete model lifecycle.
</p>

<p>
<center>
<img src="img/discretemodel_lifecycle.png" alt="Discrete model states" title="Possible states of a single discrete model" />
</center>
</p>

<p>The behaviour is similar to a filter entity, with the exception of initialization step.</p>

<p>
One important thing to note is, that when the discrete model is encapsulated by a filter (preferably a <a href="?page=entsignalgenerator">signal generator</a> filter), the <code>Execute</code> method
call is chained through the discrete model, as depicted in figure below.
</p>

<p>
<center>
<img src="img/discretemodel_execute.png" alt="Discrete model execute method call" title="Execute method call chain" />
</center>
</p>

<h3>Example implementation</h3>

<!-- descriptor, do_create_discrete_model, stepping jednoducheho modelu -->

<p>
At first, we usually create a header defines for our model to describe the purpose and basic idea. As an example, let us define a model, that generates a signal in a "saw" form (increasing until a certain point, in which it
resets to a base value). Please note, that this is a very simple example of such a model and merely describes the interface and operation of discrete models.
</p>
<p>
The structures for a model usually falls to a descriptor file header:
</p>
<code><pre>
namespace example_discrete_model {

    constexpr GUID model_id = { 0x1ac12918, 0x9a9c, 0x144f, { 0xaf, 0x12, 0x45, 0x8c, 0xf1, 0x53, 0x57, 0xe5 } };

    constexpr GUID saw_signal_id = { 0x9a7dd21a, 0x1744, 0xfe6a, { 0x89, 0x90, 0xa4, 0x91, 0x3b, 0xa, 0x11, 0x21 } };

    const size_t param_count = 3;

    // we represent model parameters by named fields and also as a vector
    struct TParameters {
        union {
            struct {
                double base, step_per_minute, threshold;
            };
            double vector[param_count];
        };
    };

    const TParameters lower_bounds = { { 0.0, 0.05, 5.0 } };
    const TParameters default_parameters = { { 5.0, 0.1, 10.0 } };
    const TParameters upper_bounds = { { 10.0, 0.5, 20.0 } };
}
</pre></code>

<p>
Now, we can define the discrete model class (using the SmartCGMS SDK):
</p>

<code><pre>
class CExample_Discrete_Model : public scgms::CBase_Filter, public scgms::IDiscrete_Model {

    private:
        example_discrete_model::TParameters mParameters;

    protected:
        uint64_t mSegment_Id = scgms::Invalid_Segment_Id;
        double mCurrent_Time = 0;
        double mCurrent_Value = 0;

    protected:
        // scgms::CBase_Filter iface implementation
        virtual HRESULT Do_Execute(scgms::UDevice_Event event) override final {

            // just pass the event further
            return mOutput.Send(event);
        }
        virtual HRESULT Do_Configure(scgms::SFilter_Configuration configuration, refcnt::Swstr_list& error_description) override final {

            mCurrent_Value = mParameters.base;

            return S_OK;
        }

    public:
        CExample_Discrete_Model(scgms::IModel_Parameter_Vector *parameters, scgms::IFilter *output);
        virtual ~CExample_Discrete_Model() = default;

        // scgms::IDiscrete_Model iface
        virtual HRESULT IfaceCalling Initialize(const double current_time, const uint64_t segment_id) override final {
            
            if (mSegment_Id != scgms::Invalid_Segment_Id) {
                return E_ILLEGAL_STATE_CHANGE;
            }

            mSegment_Id = segment_id;
            mCurrent_Time = current_time;

            return S_OK;
        }

        virtual HRESULT IfaceCalling Step(const double time_advance_delta) override final {
            
            if (mSegment_Id == scgms::Invalid_Segment_Id) {
                return E_ILLEGAL_METHOD_CALL;
            }

            if (time_advance_delta < 0.0) {
                return E_INVALIDARG;
            }

            if (time_advance_delta > 0.0) {

                mCurrent_Time += time_advance_delta;
                mCurrent_Value += (time_advance_delta / scgms::One_Minute) * mParameters.step_per_minute;

                if (mCurrent_Value > mParameters.threshold) {
                    mCurrent_Value = mParameters.base;
                }
            }

            scgms::UDevice_Event evt{ scgms::NDevice_Event_Code::Level };

            evt.device_id() = example_discrete_model::model_id;
            evt.device_time() = mCurrent_Time;
            evt.level() = mCurrent_Value;
            evt.signal_id() = example_discrete_model::saw_signal_id;
            evt.segment_id() = mSegment_Id;

            return mOutput.Send(evt);
        }
};
</pre></code>

<p>
Then, we define a descriptor block:
</p>
<code><pre>
// we will sure use the following headers
#include &lt;utils/descriptor_utils.h&gt;
#include &lt;iface/DeviceIface.h&gt;
#include &lt;lang/dstrings.h&gt;
#include &lt;rtl/manufactory.h&gt;

namespace example_discrete_model {

    const wchar_t *model_param_ui_names[model_param_count] = {
        L"Saw base value",
        L"Increment per minute",
        L"Upper threshold"
    };

    const scgms::NModel_Parameter_Value model_param_types[model_param_count] = {
        scgms::NModel_Parameter_Value::mptDouble,
        scgms::NModel_Parameter_Value::mptDouble,
        scgms::NModel_Parameter_Value::mptDouble
    };

    constexpr size_t number_of_calculated_signals = 1;

    const GUID calculated_signal_ids[number_of_calculated_signals] = {
        saw_signal_id
    };

    const wchar_t* calculated_signal_names[number_of_calculated_signals] = {
        L"Saw signal"
    };

    const GUID reference_signal_ids[number_of_calculated_signals] = {
        Invalid_GUID
    };

    scgms::TModel_Descriptor desc = {
        model_id,
        scgms::NModel_Flags::Discrete_Model,
        L"Example discrete model (saw)",
        nullptr,
        model_param_count,
        0,
        model_param_types,
        model_param_ui_names,
        nullptr,
        lower_bounds.vector,
        default_parameters.vector,
        upper_bounds.vector,

        number_of_calculated_signals,
        calculated_signal_ids,		
        reference_signal_ids,
    };

    // for more info, see signal subpage
    const scgms::TSignal_Descriptor saw_signal_desc = {
        saw_signal_id,
        L"Saw signal",
        L"",
        scgms::NSignal_Unit::Unitless,
        0xFFFF0000,
        0xFFFF0000,
        scgms::NSignal_Visualization::smooth,
        scgms::NSignal_Mark::none,
        nullptr
    };
}
</pre></code>

<p>
The block above contains a definition of signal (its descriptor). For more details about the descriptor contents, see <a href="?page=apisignal">signal</a> subpage.
</p>

<p>
Then we have to define and export <code>do_get_model_descriptors</code> and <code>do_get_signal_descriptors</code> functions:
</p>

<code><pre>
const std::array<scgms::TModel_Descriptor, 1> model_descriptors = { { example_discrete_model::desc } };

const std::array<scgms::TSignal_Descriptor, 1> signal_descriptors = { { example_discrete_model::saw_signal_desc } };

HRESULT IfaceCalling do_get_model_descriptors(scgms::TModel_Descriptor **begin, scgms::TModel_Descriptor **end) {
    return do_get_descriptors(model_descriptors, begin, end);
}

HRESULT IfaceCalling do_get_signal_descriptors(scgms::TSignal_Descriptor **begin, scgms::TSignal_Descriptor **end) {
    return do_get_descriptors(signal_descriptors, begin, end);
}
</pre></code>

<p>
The last step is to implement the factory method <code>do_create_discrete_model</code>:
</p>

<code><pre>
HRESULT IfaceCalling do_create_discrete_model(const GUID *model_id, scgms::IModel_Parameter_Vector *parameters, scgms::IFilter *output, scgms::IDiscrete_Model **model) {
    if (*model_id == example_discrete_model::model_id) {
        return Manufacture_Object<CExample_Discrete_Model>(model, parameters, output);
    }

    return E_NOTIMPL;
}
</pre></code>
