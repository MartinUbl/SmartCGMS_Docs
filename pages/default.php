<h2>Developer zone</h2>

<p>Welcome to SmartCGMS developer zone. This is the home of SmartCGMS documentation.</p>

<p>
SmartCGMS is a software architecture and framework for signal processing. It comprises a set of configurable, interchangable modules, which may be used for signal synthesis, analysis, error calculation and much more. Each configuration connects a number of 
top-level modules called <i>filters</i>. Filters are connected in a linear uni-directional chain, where each subsequent filters communicate by sending messages in forward direction. The message passes from message source to the end of the chain. Each filter may
read, modify or consume (destroy) the message, depending on its implementation. In SmartCGMS architecture, we refer to the message as to a <i>device event</i>. The device event structure is further described in a <a href="?page=apideviceevent">separate page</a>
</p>

<p>
Every entity (filter, signal, ...) is identified by an unique GUID. It also has to export a descriptor via standard <a href="?page=apilibraryinterface">library interface</a>. This descriptor contains all information needed to create an entity and pass a set of 
parameters, if needed.
</p>

<p>
A common scenario typically includes one device event source at the beginning of the chain. Then, a number of signal processing filters follow. Usually, there are one or more output filters at the end of the chain, which generates outputs (e.g. drawings),
stores the simulation result into a log file and so on.
</p>

<p>One such example of a scenario may be the following setup:
<center>
<img src="img/arch.png" alt="Simplified scenario" title="Simplified scenario demonstrating the basic SmartCGMS principles of operation" />
</center>
</p>
<p>
This is a very simplified scenario, where one filter reads previously measured CGM profile from database (regulated with insulin pump only), the following filter calculating the signal of a LGS controller algorithm,
and the next filter evaluating the average differences between the profile-recorded treatment and newly calculated treatment. Also, the whole simulation is rendered into SVG drawings and logged into a file.
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

<p>
What is a typical signal processing filter?
<ul>
    <li>a synchronous signal generator <a href="?page=apifilter">filter</a>, synchronized to a source signal</li>
    <li>a signal mapping <a href="?page=apifilter">filter</a>, changing the GUID of signal (and as a result, changes its meaning)</li>
    <li>a signal masking <a href="?page=apifilter">filter</a>, masking the signal values using a bit mask, to create training/testing dataset</li>
    <li>a calculated signal <a href="?page=apifilter">filter</a>, calculating another signal value based on newly received signal level</li>
    <li>and so on...</li>
</ul>
</p>

<p>
What is a typical output filter?
<ul>
    <li>a drawing <a href="?page=apifilter">filter</a>, creating plots and figures of available signals</li>
    <li>a log <a href="?page=apifilter">filter</a>, storing all device events to a log file</li>
    <li>an error metric <a href="?page=apifilter">filter</a>, calculating error metric between a reference and an error signal</li>
    <li>and so on...</li>
</ul>
</p>

<p>
What other kind of filters can I use?
<ul>
    <li>a native scripting <a href="?page=apifilter">filter</a>, running a custom C++ script from a file</li>
    <li>a noise generator <a href="?page=apifilter">filter</a>, enhancing a signal with noise</li>
    <li>a feedback sender <a href="?page=apifilter">filter</a>, to create a feedback loop, obtaining e.g.; closed loop control system</li>
    <li>and any other filter you develop</li>
</ul>
</p>
