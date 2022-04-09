<h2>Model</h2>

<p>
A model is either a <a href="?page=apisignalmodel">signal model</a> or a <a href="?page=apidiscretemodel">discrete model</a>. Both shares the same descriptor structure and library interface.
</p>

<p>
The main difference between a signal model and a discrete model is, that a signal model is stateless. The discrete model has a state and therefore requires an additional (constant) memory.
As a result of this difference, a discrete model is purely of an incremental character - once the state is updated, the model is not able to produce past values. The signal model, having all
required reference signal values, is able to produce values from any requested time. Both types are further described in their respective subpages.
</p>

<h3>Descriptor</h3>

<p>Every model is described by its descriptor (<code>scgms::TModel_Descriptor</code>):</p>

<code><pre>
struct TModel_Descriptor {
	const GUID id;
	const NModel_Flags flags;
	const wchar_t *description;
	const wchar_t *db_table_name;
	const size_t number_of_parameters;
	const NModel_Parameter_Value *parameter_types;
	const wchar_t **parameter_ui_names;
	const wchar_t **parameter_db_column_names;
	const double *lower_bound;
	const double *default_values;
	const double *upper_bound;
	const size_t number_of_calculated_signals;
	const GUID* calculated_signal_ids;
	const GUID* reference_signal_ids;
};
</pre></code>
<p>
<ul>
    <li><code>id</code> in an unique model GUID</li>
    <li><code>flags</code> represents a model feature flags, a value of <code>NModel_Flags</code>, see below</li>
    <li><code>description</code> is a string representing model name, displayed in user interface; it must not be <code>nullptr</code> and must contain a valid zero-terminated string</li>
    <li><code>db_table_name</code> (<i>deprecated</i>) identifies database table name used to store this model parameters</li>
    <li><code>number_of_parameters</code> is a number of model parameters (and a size of following 6 arrays), must be greater than 0</li>
    <li><code>parameter_types</code> is an array of size <code>number_of_parameters</code>, containing parameter types from <code>NModel_Parameter_Value</code> enumerator (see below)</li>
    <li><code>parameter_ui_names</code> is an array of size <code>number_of_parameters</code>, containing the human-readable parameter names of each parameter</li>
    <li><code>parameter_db_column_names</code> (<i>deprecated</i>) is an array of size <code>number_of_parameters</code>, containing the database table column names of each parameter</li>
    <li><code>lower_bound</code> is an array of size <code>number_of_parameters</code>, containing each parameter lower bound (for e.g. optimalization)</li>
    <li><code>default_values</code> is an array of size <code>number_of_parameters</code>, containing a default value of each parameter</li>
    <li><code>upper_bound</code> is an array of size <code>number_of_parameters</code>, containing each parameter upper bound (for e.g. optimalization)</li>

    <li><code>number_of_calculated_signals</code> is a number of signals this model may produce (and size of following 2 arrays)</li>
    <li><code>calculated_signal_ids</code> is an array of size <code>number_of_calculated_signals</code>, containing GUIDs of calculated signals (the signal GUIDs that this model may emit at any time)</li>
    <li><code>reference_signal_ids</code> is an array of size <code>number_of_calculated_signals</code>, containing GUIDs of reference signals for each calculated signal (to e.g. calculate signal errors by appropriate filters)</li>
</ul>
</p>

<p>
    The <code>NModel_Flags</code> enumerator currently contains only two values: <code>Signal_Model</code> to identify a signal model and <code>Discrete_Model</code> to identify discrete model. The use of these
    two flags are not exclusive. Therefore, a model could be both signal model and discrete model, although it is not a common scenario.
</p>

<p>
    The <code>NModel_Parameter_Value</code> enumerator values serve as a semantic marker for each parameter. All parameters are internally stored as double precision floating point values. This enumerator
    disambiguates between the following types:
<ul>
    <li><code>mptDouble</code> - an ordinary double-precision floating point number</li>
    <li><code>mptTime</code> - a double-precision floating point number representing <a href="?page=apitime">rat time</a></li>
    <li><code>mptBool</code> - a boolean value; zero value is interpreted as <code>false</code>, any other value as <code>true</code></li>
</ul>
</p>

<p>
As stated above, a model defines a set of signals it produces. The philosophy is different for each type of model. A signal model defines its distinct set of signals, which it must produce, and
a discrete model defines a set of any signals (already existing or newly introduced ones) it may produce.
</p>

