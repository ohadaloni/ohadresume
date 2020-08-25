M is a PHP MVC (Model View Controller) framework.

This site is built with M.

With M one creates controllers by extending Mcontroller.

Mcontroller->Mview is an extension of the Smarty template system
To create the servral views, only the templates are created and shown.

Mcontroller->Mmodel is the Model layer.
Mmodel potentially caches the data from its queries automatically,
with ttl (time to live) controlled by the calling code.
