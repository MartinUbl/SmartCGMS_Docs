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
Typically, <!-- terminal filtr - zahazuje eventy -->
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
One may choose to implement this interface 
</p>


<h3>Operation</h3>



<h3>Example implementation</h3>

<!-- descriptor, do_get_filter_descriptors, do_create_filter, implementace IFilter, použití CBase_Filter (SDK) -->
