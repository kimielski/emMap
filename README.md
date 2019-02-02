emMap - Joomla map plugin 
=============

Plugin for displaying embed maps by  Google Maps. Can be used for replace map created by System - Google Maps plugin ({mosmap}).  
It works without API key.

Instructions
============

To install plugin:

1. Click on the button to download the .zip package
2. Log into your site's administrator section, go to Extension Manager > Install, and install the extension

Usage
=============
Just place in Your content short tag:  

*Address:*  
`{emmap width='400'|height='200'|address='Cracow,Pawia 16,Poland'|zoom='8'}`  
 *Latitude, Longitude:*  
`{emmap width='400'|height='200'|centerlat='38.446531'|centerlon='14.953766'|zoom='8'}`  

Parameters
=============
**width** - map container width in px  
**height** - map container height in px  
**address** - address that will be visible on the map  
**zoom** - map zoom (0-18)  
**centerlat** - (latitude) geographic coordinate that specifies the north–south position of a point on the Earth's surface.  
**centerlon** - (longitude) geographic coordinate that specifies the east–west position of a point on the Earth's surface.  

**Important!** 
If You define both options: address and centerlat, centerlon in the same map - address will be ignored.


License
=======
The emMap follow's  the [GNU General Public License Version 2 or Later](http://www.gnu.org/licenses/gpl-2.0.txt).