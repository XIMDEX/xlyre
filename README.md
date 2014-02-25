#XLYRE

##Introduction

Xlyre is an Open Data Management Module for Ximdex CMS. It can be used to publish sets of data with their metadata. It's based on three main components:

* Catalog
* Dataset 
* Distribution

A catalog is a list of datasets that describe a collection of entities. Each dataset has several metadata fields and a set of distributions associated with the dataset. Each distribution is a set of data in a specific format or based in a specific period od time (month, year, ...), etc.

##Installation

Before install Xlyre you have to edit a file called install-modules.conf that it's located in conf folder. One you open this file with your favourite text editor you have to add the following line:

    define('MODULE_XLYRE_PATH','/modules/xlyre');

Once this line is added to install-modules.conf file you can install Xlyre. First go to modules folder and copy module files into it. Then go to install folder in Ximdex path and execute:

    ./module.sh install xlyre

That's it! Now you will see specific actions and configuraitons for Xlyre on Ximdex CMS.


##Screenshots

* Create a catalog with one dataset:

![Xlyre Catalog screenshot](https://dl.dropboxusercontent.com/s/af6yfyll7l9u1fp/catalog.png "Xlyre Catalog screenshot")

* Edit a dataset:

![Xlyre Dataset screenshot](https://dl.dropboxusercontent.com/s/7ixej7lavzdu99w/dataset.png "Xlyre Dataset screenshot")

* Add new distribution in a specific dataset:

![Xlyre Add Distribution screenshot](https://dl.dropboxusercontent.com/s/58k4gko5aie3oaz/distribution1.png "Xlyre Add Distribution screenshot")

* Show distribution in a specific dataset:

![Xlyre Show Distribution screenshot](https://dl.dropboxusercontent.com/s/98jydnmdja63c8c/distribution2.png "Xlyre Show Distribution screenshot")

