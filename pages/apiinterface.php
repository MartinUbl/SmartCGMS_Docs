<h2>Object model and interface</h2>

<p>
    Each entity within the interoperable context of SmartCGMS also implements the <code>refcnt::IReferenced</code> interface. This interface is a custom variant of a standard
    <code>IUnknown</code> interface, which is a basic interface of a COM (Component Object Model). The SmartCGMS architecture follows the COM model and expects the entities to
    behave in such way. If the developer develops the component using the C++ language, most of the COM-related functionality can be achieved by a proper inheritance of SDK classes).
</p>

<p>
    SmartCGMS SDK offer the interface definition and partial implementation within the <code>refcnt</code> namespace:
<ul>
<li><code>refcnt::IReferenced</code> - the base interface, usually known as <code>IUnknown</code>; defines three methods (and also the following <i>vtable</i> layout):
    <ul>
        <li><code>QueryInterface</code> - a method used to ask the component, if it supports the requested interface</li>
        <li><code>AddRef</code> - increments the object reference count</li>
        <li><code>Release</code> - decrements the object reference count; if the count reaches zero, the object gets deallocated</li>
    </ul>
</li>
<li><code>refcnt::CReferenced</code> - base implementation of an object, which should maintain reference counting (<code>AddRef</code> and <code>Release</code> changes the value of
the reference count attribute)
</li>
<li><code>refcnt::CNot_Referenced</code> - base implementation of an object, which does not participate in a reference-counted context, i.e. something else manages the object lifetime</li>
</ul>
Every entity, apart from its entity-specific interface, must also correctly implement the <code>refcnt::IReferenced</code> interface, or inherit from either <code>refcnt::CReferenced</code>
or <code>refcnt::CNot_Referenced</code> SDK implementation.
</p>

<p>The following figure depicts the possible class diagram of a custom filter:
<center>
<img src="img/filter_inheritance.png" alt="Filter inheritance diagram" title="Filter inheritance diagram" />
</center>
</p>

<p>Though it is much more convenient to use the intermediate class <code>scgms::CBase_Filter</code>, also present in SDK:
<center>
<img src="img/filter_inheritance_2.png" alt="Filter inheritance diagram" title="Filter inheritance diagram" />
</center>
</p>

<p>
    Throughout the SmartCGMS, reference-counted vectors are often used. A common interface <code>refcnt::IVector_Container</code> is defined in the SDK. This interface defines a set of methods:
<ul>
<li><code>set</code> - sets the content of this container to a given range (<code>begin</code> to <code>end</code>)</li>
<li><code>add</code> - adds a range of elements</li>
<li><code>get</code> - retrieves the range of contained elements</li>
<li><code>pop</code> - removes and returns exactly one element from the container</li>
<li><code>remove</code> - removes one given item from the container</li>
<li><code>move</code> - moves the element of the container from source index to destination index</li>
<li><code>empty</code> - determines, if the container is empty</li>
</ul>
The underlying implementation should therefore maintain a continuous memory, which might be resized during container lifetime. In C++ environment, <code>std::vector</code>
complies with given requirements. The SmartCGMS SDK offers the generic <code>refcnt::CVector_Container</code> implementation and its variants.
</p>

<p>
Each object implementing the <code>refcnt::IReferenced</code> interface is required to implement the <code>QueryInterface</code> method. This method is used to declare support for
a GUID-identified interface. If the interface is supported, the <code>QueryInterface</code> method sets typecasted this pointer to the interface and <code>S_OK</code>. Otherwise, it returns
<code>E_NOINTERFACE</code>.
</p>
<p>This is often used to support <a href="?page=apiinspection">entity inspection</a> interfaces.</p>
