<h2>Entities</h2>

<p>
The SmartCGMS architecture recognizes the following types of entities:
<ul>
    <li><a href="?page=apifilter">filters</a> - a base entity, maintains one isolated piece of work</li>
    <li><a href="?page=apidiscretemodel">discrete models</a> - generates signals with a fixed stepping</li>
    <li><a href="?page=apisignalmodel">signal models</a> - generates signals as a result of another incoming signal level</li>
    <li><a href="?page=apimetric">metrics</a> - evaluates the differences between two signals</li>
    <li><a href="?page=apisolver">solvers</a> - optimizes parameters of a given model using a programmer-defined objective function</li>
    <li><a href="?page=apiapproximator">approximators</a> - approximates a discrete signal, calculates (or estimates) its derivation</li>
    <li><a href="?page=apisignal">signals</a> - containers for a set of signal levels</li>
</ul>
</p>

<p>
Each entity has its own GUID and descriptor. A single entity should be managed by a single shared object (dynamic library). The shared object that exported the entity descriptor
(via the <code>do_get_*_descriptors</code>) must also be able to create it using appropriate factory method (<code>do_create_*</code>).
</p>
