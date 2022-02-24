<h2>API overview</h2>

<!-- cleneni do dynamickych knihoven, scgms.dll, odkaz na COM/IUnknown, ... -->

<p>
SmartCGMS is a framework with one central element - the <code>scgms</code> library. This library, formerly known as <code>scgms factory</code>, is an entry point for creating and managing SmartCGMS entities, configuring and controlling the simulation
and resource management. The library itself is compiled as a dynamic library (<code>scgms.dll</code> on MS Windows, <code>libscgms.so</code> on Linux-based systems, <code>libscgms.dylib</code> on macOS, etc.) and exports a number of functions, further listed on
a <a href="?page=apilibraryinterface">separate page</a>. These functions are often called through the supplied C++ SDK.
</p>

<p>
The SmartCGMS entity API definition consist of two basic interface definitions:
<ul>
    <li>library interface - a library must export a set of functions to indicate, that it contains the implementation of given entity and is able to create an instance of it</li>
    <li>entity interface - any entity must comply with the interface definition of its kind (e.g.; filter must implement <code>scgms::IFilter</code> interface, etc.)</li>
</ul>
</p>

<p>
The <code>scgms</code> library expects the following directory structure (demonstrated on MS Windows naming conventions):
<ul>
    <li><code>filters</code>
        <ul>
            <li><code>customfilter.dll</code></li>
            <li><code>data.dll</code></li>
            <li><code>metrics.dll</code></li>
            <li>...</li>
        </ul>
    </li>
    <li><code>scgms.dll</code></li>
    <li><code>frontend.exe</code> <i>(optional, just to demonstrate the usual placement)</i></li>
</ul>
It is important to note, that <code>scgms</code> library loads only dynamic libraries, placed in the <code>filters</code> subdirectory, following the platform-specific shared object
naming conventions.
</p>

<p>
For example, if a developer intends to introduce a new filter, he has to create a new dynamic library (or enhance an existing one), export <code>do_get_filter_descriptors</code> and <code>do_crete_filter</code> and place it into the filters directory just
next to the library itself. The <code>scgms</code> library then registers the newly created dynamic library as a part of SmartCGMS and loads descriptors from it. The developer then
needs to create a descriptor of the entity he intend to implement, assign an unique GUID (preferably using a generator) and let the appropriate function return it. When the user
creates a configuration, that contains the newly developed filter, the <code>scgms</code> library calls the matching <code>create_filter</code> function. The developer thus needs to
implement this function to create a new entity, complying with the <code>scgms::IFilter</code> interface (further described <a href="?page=apifilter">here</a>).
</p>