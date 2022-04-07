<h2>Filter</h2>

<p>
A filter is a "top-level" entity in SmartCGMS configuration. It may manage other entity types, depending on its purpose. A single filter is configured as a single link in SmartCGMS configuration.
</p>

<h3>Descriptor</h3>

<p>
Every filter is described by its descriptor (<code>scgms::TFilter_Descriptor</code>):
</p>
<code><pre>
struct TFilter_Descriptor {
    const GUID id;
    const NFilter_Flags flags;
    const wchar_t *description;
    const size_t parameters_count;
    const NParameter_Type* parameter_type;
    const wchar_t** ui_parameter_name;
    const wchar_t** config_parameter_name;
    const wchar_t** ui_parameter_tooltip;
};
</pre></code>

<p>
<ul>
    <li><code>id</code> is an unique GUID assigned to this filter. The filter is created using this identifier.</li>
    <li><code>flags</code> represents a filter flags; this field is currently unused.</li>
    <li><code>description</code> is a string representing a name of the filter; it must not be <code>nullptr</code> and must contain a valid zero-terminated string</li>
    <li><code>parameters_count</code> represents a count of parameters (and a size of following 4 arrays)</li>
    <li><code>parameter_type</code> is an array of size <code>parameters_count</code>, containing parameter types from <code>scgms::NParameter_Type</code> enumerator (see below)</li>
    <li><code>ui_parameter_name</code> is an array of size <code>parameters_count</code>, containing the human-readable parameter names for each parameter</li>
    <li><code>config_parameter_name</code> is an array of size <code>parameters_count</code>, containing keys used for each parameter in configuration file
        <ul>
            <li>such a key should consist solely of alphanumeric characters, dash and underscore</li>
            <li>using this key, the filter is able to read the configuration parameter from the container passed to <code>Configure</code> method (see below)</li>
        </ul>
    </li>
    <li><code>ui_parameter_tooltip</code> is an array of size <code>parameters_count</code>, containing extended (human-readable) descriptions of parameters</li>
</ul>
</p>

<p>A <code>scgms::NParameter_Type</code> enumerator contains a set of pre-defined constants, which are used to represent either a data-type of given parameter,
and also a parameter semantic. Currently, the enumerator holds the following values:
<ul>
    <li><code>ptNull</code> - "empty" parameter, not interpreted, UI may choose to ignore it; currently the gpredict3-desktop frontend uses this value to create a visual separator</li>
    <li><code>ptWChar_Array</code> - string parameter (internally represented as a vector of characters to allow interoperability)</li>
    <li><code>ptInt64_Array</code> - an array of integers</li>
    <li><code>ptDouble</code> - a double-precision floating point value</li>
    <li><code>ptRatTime</code> - same as <code>ptDouble</code>, but interpreted as a time; the UI may display a date/time picker control</li>
    <li><code>ptInt64</code> - a single 64-bit (long) integer</li>
    <li><code>ptBool</code> - a boolean (true/false) value; internally represented as a single byte</li>
    <li><code>ptSignal_Model_Id</code> - a GUID of a signal model; the UI may display a combobox with known signal models</li>
    <li><code>ptDiscrete_Model_Id</code> - a GUID of discrete model; the UI may display a combobox with known discrete models</li>
    <li><code>ptMetric_Id</code> - a GUID of a metric; the UI may display a combobox with known metrics</li>
    <li><code>ptSolver_Id</code> - a GUID of a solver; the UI may display a combobox with known solvers</li>
    <li><code>ptModel_Produced_Signal_Id</code> - a GUID of a signal produced by a model (discrete or signal); the UI may display a combobox with known signals - if the configuration contains a model selector, the combobox may contain only selected model-produced signals</li>
    <li><code>ptSignal_Id</code> - a GUID of a signal; the UI may display a combobox with known signals (not necessarily produced by any known model)</li>
    <li><code>ptDouble_Array</code> - an array of double-precision floating point values</li>
    <li><code>ptSubject_Id</code> - same as <code>ptInt64</code>, but interpreted as a subject ID; this ID may come from a database</li>
</ul>
</p>

<p>A shared object exporting a filter must:
<ul>
    <li>export a <code>do_get_filter_descriptors</code> function
        <ul>
            <li>this function returns a continuous array of filter descriptors identified by its first and one-after-last element</li>
        </ul>
    </li>
    <li>create this filter, when requested by <code>do_create_filter</code> call</li>
</ul>
</p>

<p>
An example of <code>do_get_filter_descriptors</code> may be as follows:
</p>
<code><pre>
const std::array<const scgms::TFilter_Descriptor, 1> filter_descriptors = { { descriptor_1, descriptor_2 } };

HRESULT IfaceCalling do_get_filter_descriptors(scgms::TFilter_Descriptor **begin, scgms::TFilter_Descriptor **end) {
    *begin = filter_descriptors.data();
    *end = filter_descriptors.data() + filter_descriptors.size();
    return S_OK;
}
</pre></code>

<p>An example of <code>do_create_filter</code> may be as follows:</p>
<code><pre>
HRESULT IfaceCalling do_create_filter(const GUID *id, scgms::IFilter *output, scgms::IFilter **filter) {
	if (*id == descriptor_1.id)
		return Manufacture_Object<CMy_Filter>(filter, output);

	return E_NOTIMPL;
}
</pre></code>
<p>In this example, <code>Manufacture_Object</code> is a SDK function which creates an instance of <code>IReferenced</code> object and initializes its reference counter. The function is called with
GUID of desired filter, output filter pointer and target memory, in which the pointer to newly created entity should be stored. Note the <code>output</code> filter pointer gets passed here - the filter
itself is responsible for passing the device events to next filters in chain.</p>
<p>
Typically, the filter chain is configured through the SDK class <code>CFilter_Executor</code> (and its RAII reference counted wrapper, <code>SFilter_Executor</code>). Thus, the filter factory function
gets automatically called with the succeeding filter. The end of the chain should contain a terminal filter, which should properly deallocate device events passed through the filter chain. The SDK
executor includes the default terminal filter, which drops the reference count of device event in <code>Execute</code> method call, returning <code>S_OK</code>, indicating, that the event passed through
the whole chain without an error.
</p>

<h3>Interface</h3>

<p>
Every filter entity must implement the <code>scgms::IFilter</code> interface. The interface is defined as an abstract C++ class as follows:
</p>
<code><pre>
class IFilter : public virtual refcnt::IReferenced {
public:
    virtual HRESULT IfaceCalling Configure(IFilter_Configuration* configuration, refcnt::wstr_list *error_description) = 0;
    virtual HRESULT IfaceCalling Execute(scgms::IDevice_Event *event) = 0;	
};
</pre></code>

<p>The <code>Configure</code> method is called prior the full operation mode. This method passes the configuration parameters to the filter and configures it to operational state.
The return value may indicate a success (<code>S_OK</code>), success with warning (<code>S_FALSE</code>) or a fatal error (any other non-success code). Yielding a faulty result code
leads to configuration failure and fails the whole process of configuring the filter chain.</p>

<p>Once in operational state, the outer code (often wrapped in a filter executor or similar code) may choose to call the <code>Execute</code> method. This invokes the actual filter's control loop.
The device event passed to this call is passed with a move semantic - the device event is now owned by this filter. The <code>Execute</code> method must either pass the event to the next filter, or
call <code>Release</code> to properly deallocate the device event memory.</p>

<p>
One may choose to implement this interface on his own. However, SmartCGMS SDK provides the developer with base class <code>CBase_Filter</code>, implementing the repeating parts of code and wrapping
all pointers into its reference-counted RAII wrappers. This class implements the interface and defines protected pure virtual methods <code>Do_Configure</code> and <code>Do_Execute</code>. An example of 
a filter implemented using the wrapper is included at the bottom of this page.
</p>

<h3>Operation</h3>

<p>
The filter has basically three states, in which it may operate:
<ul>
    <li><i>Created</i> - the filter just got instantiated and waits for the <code>Configure</code> call.</li>
    <li><i>Operational</i> - the filter is configured and is able to process device events in <code>Execute</code> method. The <code>Execute</code> method is called exclusively in this state.</li>
    <li><i>Terminated</i> - the filter received the <code>Shut_Down</code> device event code, deallocated all its resources and waits for its deallocation by outer code. Any further <code>Configure</code> and <code>Execute</code> call on this filter instance is invalid.</li>
</ul>
The figure below illustrates the filter lifecycle.
</p>

<p>
<center>
<img src="img/filter_lifecycle.png" alt="Filter states" title="Possible states of a single filter" />
</center>
</p>

<p>
The filter may be partially reset to its initial state by sending <code>Warm_Reset</code> device code through the chain. By using this code, the filter may "remember" useful information from its previous runs,
but is should reset to its initial state. An example may include parameter optimalization - such a filter may remember newly obtained parameters, but should discard all data that the parameters are based on. 
</p>

<h3>Events passing</h3>

<p>
Device events are processed synchronously in the filter chain. This means, that when the code calls <code>Execute</code> on any filter in the chain, the call does not return until the last device event reaches
the last filter. In other words, the call to the <code>Execute</code> method is recursive. When using the standard way of working with filter chains (via <code>scgms</code> library), the execution is guarded
by a recursive mutex. Thus, only a single device event may pass through the filter chain at one time.
</p>

<p>
If the filter creates a new thread, the <code>Execute</code> calls (or <code>Send</code> calls in case of using the SDK) are all synchronized. The developer should bear in mind, that this synchronization
may occur in order to avoid deadlocks.
</p>

<h3>Example implementation</h3>

<p>
An example filter implemented using SmartCGMS SDK:
</p>
<code><pre>
// the following SDK header files are required
#include &lt;iface/DeviceIface.h&gt;
#include &lt;iface/FilterIface.h&gt;
#include &lt;rtl/FilterLib.h&gt;

class CExample_Filter : public scgms::CBase_Filter {

private:
    double mMy_Var = std::numeric_limits<double>::quiet_NaN();

protected:
    virtual HRESULT Do_Configure(scgms::SFilter_Configuration configuration, refcnt::Swstr_list& error_description) override final {

        // read Example_Config_Var (defined in descriptor, potentially contained in config.ini file)
        // the rsExample_Config_Var constant is defined in an example descriptor block below
        // if it is not found in configuration, use default value of 3.0
        mMy_Var = configuration.Read_Double(rsExample_Config_Var, 3.0);

        // maybe do some validation; do not forget to indicate an error, so the configuration process fails
        if (mMy_Var < 0.0) {
            error_description.push(L"Invalid Example_Config_Var value! Use positive values");
            return E_INVALIDARG;
        }

        // everything is configured correctly
        return S_OK;
    }

    virtual HRESULT Do_Execute(scgms::UDevice_Event event) override final {

        if (event.device_code() == scgms::NDevice_Event_Code::Shut_Down) {
            // close all files...
            // terminate all threads...
            // deallocate all memory...
        }

        // ...

        // pass the event further
        // this is not mandatory - if you do not wish to propagate events to next filters, you may just return S_OK, the UDevice_Event wrapper deallocates the device event automatically
        // and the call will unroll properly; most situations, however, requires you to pass the events to the next filter in chain:
        return mOutput.Send(event);
    }
    
public:
    CExample_Filter(scgms::IFilter *output);
    virtual ~CExample_Filter();
};
</pre></code>
<p>The implementation above demonstrates the base code for a filter. We will use this filter as an example in the following code.</p>

<p>Every filter should have its own GUID and filter descriptor. The general recommendation in SmartCGMS code is to use namespaces to contain a specific entity info and descriptions. An example
of header definition follows:
</p>
<code><pre>
namespace example_filter {

    constexpr GUID filter_id = { 0x904410ca, 0xb0aa, 0x4fb1, { 0x8f, 0x76, 0x74, 0x68, 0x80, 0x13, 0x82, 0xab } }; // {904410CA-B0AA-4FB1-8F76-7468801382AB}

    extern const wchar_t* rsExample_Config_Var;
}
</pre></code>

<p>This code may be shared between the filter code and descriptor code, and thus may be placed in a reachable header file.</p>

<p>The descriptor block example follows:</p>
<code><pre>
// we will need all of the following includes
#include &lt;iface/DeviceIface.h&gt;       // TFilter_Descriptor
#include &lt;iface/FilterIface.h&gt;       // filter flags
#include &lt;rtl/FilterLib.h&gt;           // filter parameters
#include &lt;rtl/manufactory.h&gt;         // Manufacture_Object
#include &lt;utils/descriptor_utils.h&gt;  // do_get_descriptors

namespace example_filter {

    constexpr size_t param_count = 1;

    const scgms::NParameter_Type param_type[param_count] = {
        scgms::NParameter_Type::ptDouble
    };

    const wchar_t* ui_param_name[param_count] = {
        L"Some multiplier (example)"
    };

    const wchar_t* rsExample_Config_Var = L"Example_Config_Var";

    const wchar_t* config_param_name[param_count] = {
        rsExample_Config_Var
    };

    const wchar_t* ui_param_tooltips[param_count] = {
        L"This is just an example of some parameter going to the filter in configure method"
    };

    const wchar_t* filter_name = L"My example filter";

    const scgms::TFilter_Descriptor descriptor = {
        filter_id,
        scgms::NFilter_Flags::None,
        filter_name,
        param_count,
        param_type,
        ui_param_name,
        config_param_name,
        ui_param_tooltips
    };
}
</pre></code>

<p>
Now, we have to export the descriptor and the filter itself in the <code>do_get_filter_descriptors</code> and <code>do_create_filter</code> functions. An example of these functions follows:
</p>

<code><pre>
// we often summarize known descriptors in an array or vector (continuous memory container)
const std::array<scgms::TFilter_Descriptor, 1> filter_descriptors = { { example_filter::descriptor } };

extern "C" HRESULT IfaceCalling do_get_filter_descriptors(scgms::TFilter_Descriptor **begin, scgms::TFilter_Descriptor **end) {

    // do_get_descriptors is SDK function
	return do_get_descriptors(filter_descriptors, begin, end);
}

extern "C" HRESULT IfaceCalling do_create_filter(const GUID *id, scgms::IFilter *output, scgms::IFilter **filter) {

    // is this our filter? If yes, instantiate it!
	if (*id == example_filter::descriptor.id) {
		return Manufacture_Object<CExample_Filter>(filter, output);
	}

    // we do not know how to instantiate such filter
	return E_NOTIMPL;
}
</pre></code>
