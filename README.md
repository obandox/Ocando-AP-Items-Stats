# Ocando-AP-Items-Stats
	Ocando AP Items Stats API Challenge

# Web App - Ap Items
	 http://ocando.vnz.la
	
**Note: all items are order by usage 
# API USAGE

* ///////////////////////////////////////////////////////////////////////////////////
ROUTE:
	url: "http://ocando.vnz.la/api/v1/{region}/{version}/normal_5x5/items/{type?}"

 * REGION:
	- br
	- oce
	- lan
	- las
	- na

 * VERSION:
	- 5.11
	- 5.14


  TYPE:	
   - gold
   - armor
   - health
   - mana
   - magicresist
   - abilitypower
   - attackdamage
   - attackspeed
   - cooldownreduction
   - movement
   - criticalstrike

** NOTE: without type only take ap items changes between 5.11 - 5.14

E.Gs
	"http://ocando.vnz.la/api/v1/lan/5.11/normal_5x5/items"
	"http://ocando.vnz.la/api/v1/lan/5.11/normal_5x5/items/magicresist"
	"http://ocando.vnz.la/api/v1/lan/5.11/normal_5x5/items/attackdamage"
* ////////////////////////////////////////////////////////////////////////////////////
