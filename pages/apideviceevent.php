<h2>Device event</h2>

<p>
A device event is a primary messaging structure, which is used to communicate between entities (primarily filters). The SDK defines the structure <code>scgms::TDevice_Event</code> with the
following memory layout:
</p>
<code><pre>
struct TDevice_Event {
	NDevice_Event_Code event_code;

	GUID device_id;
	GUID signal_id;

	double device_time;
	int64_t logical_time;

	uint64_t segment_id;

	union {
		double level = 0.0;
		IModel_Parameter_Vector* parameters;
		refcnt::wstr_container* info;
	};
};
</pre></code>

<p>
The meaning of all structure members are:
<ul>
    <li><code>event_code</code> - the identifier of data contained within this device event, see below for possible values</li>
    <li><code>device_id</code> - GUID of device (filter, model) that produced this device event</li>
    <li><code>signal_id</code> - GUID of signal, that is carried by this device event</li>
    <li><code>device_time</code> - rat time of this device event creation, see <a href="?page=apitime">time and time segments page</a></li>
    <li><code>logical_time</code> - logical time of this device event; increasing sequence of numbers are assigned, preferably by a single global arbiter (the <code>scgms</code> library achieves that by centralizing the device event creation)</li>
    <li><code>segment_id</code> - ID of time segment, see <a href="?page=apitime">time and time segments page</a></li>
    <li>value union
        <ul>
            <li><code>level</code> - holds a signal level value</li>
            <li><code>parameters</code> - holds a vector of parameters</li>
            <li><code>info</code> - holds a string</li>
        </ul>
    </li>
</ul>
</p>

<p>
The <code>event_code</code> field contains one of the values from <code>scgms::NDevice_Event_Code</code> enumeration:
<ul>
    <li><code>Nothing</code> - this value is considered invalid outside the entity context; this should only represent an internal device event</li>
    <li><code>Shut_Down</code> - shuts down the entity operation and releases it from memory, if possible</li>
    <li><code>Level</code> - holds signal value</li>
    <li><code>Masked_Level</code> - holds masked signal value ("hidden" level, masked by a masking filter in order to create e.g.; testing dataset)</li>
    <li><code>Parameters</code> - holds the newest set of parameters of a model</li>
    <li><code>Parameters_Hint</code> - holds the hint for a model parameters (a set of previously identified parameters used e.g.; as an initial population member in solvers)</li>
    <li><code>Suspend_Parameter_Solving</code> - suspends parameter solving in filters, that solves parameters repeatedly during simulation</li>
    <li><code>Resume_Parameter_Solving</code> - resumes parameter solving in filters, that solves parameters repeatedly during simulation</li>
    <li><code>Solve_Parameters</code> - explicitly requests the solver to be run on a given parameter-solving filter</li>
    <li><code>Time_Segment_Start</code> - marks the start of a time segment, see <a href="?page=apitime">time and time segments page</a> for more info</li>
    <li><code>Time_Segment_Stop</code> - marks the end of a time segment, see <a href="?page=apitime">time and time segments page</a> for more info</li>
    <li><code>Warm_Reset</code> - resets the entity to an initial state, but preserving any useful data (e.g.; solved parameters)</li>
    <li><code>Information</code> - holds the information string</li>
    <li><code>Warning</code> - reports warning, holds the warning detail string</li>
    <li><code>Error</code> - reports a runtime error, holds the error detail string</li>
</ul>
</p>