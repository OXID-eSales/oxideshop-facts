OXID eShop facts
================

This component is dedicated to provide primary information/facts about current
eShop installation. The information is provided in two ways:

* Printed to ``STDOUT`` (*triggered with* ``VERBOSE=1`` *environment variable*);
* Exposed to sub-processes as environment variables.

Output
------

The following information is provided after executing the script:

* ``ESHOP_PHP_BIN_PATH`` - Full path to PHP binary file which will be used to
  run scripts through CLI;
* ``ESHOP_FACT_BIN_PATH`` - Full path of current ``oe-eshop-facts`` script being
  executed;
* ``ESHOP_FACT_BASEDIR`` - Full path to a directory where other fact related
  script can be found;
* ``ESHOP_SOURCE_PATH`` - Full path to eShop runtime source and front
  controllers;
* ``ESHOP_BOOTSTRAP_PATH`` - Full path to eShop ``bootstrap.php`` file;
* ``ESHOP_CONFIG_PATH`` - Full path to eShop ``config.inc.php`` file;
* ``ESHOP_VENDOR_PATH`` - Full path to composer's ``vendor`` directory;
* ``ESHOP_VENDOR_BIN_PATH`` - Full path to binary files which were installed by
  defined dependencies inside ``composer.json`` file;
* ``ESHOP_VENDOR_NAME`` - Composer vendor name used as identifier for
  OXID eSales AG;
* ``ESHOP_OXID_VENDOR_PATH`` - Full path to directory which contains all the
  packages/components from OXID eSales AG.

Keep in mind that it's possible to override any variable from the list above
by providing it as an environment variable, e.g. in order to change the path to
PHP binary:

``ESHOP_PHP_BIN_PATH=/usr/local/bin/php ./vendor/bin/oe-eshop-facts``

Input
-----

The following environment variables are accepted:

* ``VERBOSE`` - Enables verbose mode which prints out all facts to ``STDOUT``;
* ``ESHOP_VERBOSE_FACTS`` - Enables verbose mode only for the current script.

Script execution
----------------

Current ``oe-eshop-facts`` script can be used for execution of other scripts
which are installed by composer packages/components. The given script will have
all of the facts accessable from the output section above and provided as
environment variables. In order to execute other script just provide it as an
command line argument, e.g.

``./vendor/bin/oe-eshop-facts my_custum_script``

Keep in mind that the provided script must be within the same directory as
the ``oe-eshop-facts`` file.

Custom composer commands
------------------------

Fact script can be used to provide additional commands to composer as it
provides all necessary information to reach eShop files, e.g.

To have the following command valid ``composer my_custom_script`` just add:

.. code::

  ...
  "require": {
    ...
    "oxid-esales/eshop-facts": "dev-master"
  },
  scripts: {
    "my_custom_script": "oe-eshop-facts my_custom_script"
  }
  ...

Potential usages
----------------

To get better understanding where this component can be used, few examples to
explore:

* Integrate 3rd party database migration tools
* Execute eShop internal methods through CLI

  * Regenerate database views
  * Clean eShop specific cache

* Modify eShop's configuration through the help of CLI
* Import/export eShop data through CLI
* Various bulk operations through CLI
