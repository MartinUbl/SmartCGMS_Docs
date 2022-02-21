<h2>Welcome</h2>

<p>Welcome to SmartCGMS developer zone. This is the home of SmartCGMS documentation.</p>

<p>
SmartCGMS is a software architecture and framework for signal processing. It comprises a set of configurable, interchangable modules, which may be used for signal synthesis, analysis, error calculation and much more. Each configuration connects a number of 
top-level modules called <i>filters</i>. Filters are connected in a linear uni-directional chain, where each subsequent filters communicate by sending messages in forward direction. The message passes from message source to the end of the chain. Each filter may
read, modify or consume (destroy) the message, depending on its implementation. In SmartCGMS architecture, we refer to the message as to a <i>device event</i>. The device event structure is further described in a <a href="?page=apideviceevent">separate page</a>
</p>

<p>
A common scenario typically includes one device event source at the beginning of the chain. Then, a number of signal processing filters follow. Usually, there are one or more output filters at the end of the chain, which generates outputs (e.g. drawings),
stores the simulation result into a log file and so on.
</p>

<p>
What is a typical device event source?
<ul>
    <li>a database reading <a href="?page=apifilter">filter</a>, which reads a set of previously measured (CGM) values from database</li>
    <li>a log replay <a href="?page=apifilter">filter</a>, which replays previously stored simulation state</li>
    <li>an asynchronous signal generator <a href="?page=apifilter">filter</a> with a given <a href="?page=apidiscretemodel">discrete model</a> (e.g. a metabolism model)</li>
    <li>and so on...</li>
</ul>
</p>