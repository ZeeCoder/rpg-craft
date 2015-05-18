<div id="newsWrapper">
	<h2><?php echo $l_config["news_title"]; ?></h2>
	<div>
		<p>|| Log 05. ||</p>
		<p>
			Elkészült a karakterlap 0.7-es verziója.<br />
			Részletek a 'Fejlesztők' menüpont alatt, az új dokumentációban.<br/>
			Itt található meg a Karakterlap Fejlesztő eszköz is.<br />
			<br />
			Az újítások miatt elképzelhető, hogy lesz játék ami nem indul/nem úgy működik ahogy az elvárt. Ezekről a Facebook csoportban várok értesítést.
		</p>
		<p>Hubert Viktor - 2012. november 4.</p>
	</div>
	<div>
		<p>|| Log 04. ||</p>
		<p>
			Sok fejlesztés, hibajavítás történt. Mind dokumentálva van a "Fejlesztések" menüpontban.
		</p>
		<p>Hubert Viktor - 2012. október 20.</p>
	</div>
	<div>
		<p>|| Log 03. ||</p>
		<p>
		Sooo, azt se tudom hol kezdjem.<br/>
		Rengeteg apró módosítást vittem be a rendszerbe, egy-két grafikai elem is bekerült - most kísérletezek a Photoshoppal -, fejlődött az in-game kinézet is, illetve ami talán a legfontosabb: elkészült az új, XML-alapú karakterlap.<br/>
		Ennek a működéséhez készítettem egy pdf-es dokumentációt, illetve csatoltam mellé egy példát is 'Example 1.0' néven.<br/>
		-- <a href="http://rpg.hv-web.hu/XML_docs.zip">dokumentáció letöltése példával</a> --<br/>
		Amit köv. félévre, illetve nyárra tervezek megvalósítani (így ebben a félévben még nem lesz kész):<br />
		<ul>
			<li>karakterlapban: mezők egymástól való függősége</li>
			<li>webes dokumentációs oldal (pdf helyett)</li>
			<li>Google Hangouts Api in-game</li>
			<li>Example 1.1 (Egy részletesebb karakterlap-példa)</li>
			<li>Térképen 'ping'-elés, és rajzolás</li>
			<li>Videó linkelés KM-által ingame</li>
			<li>Több grafikus téma a lobby, és ingame felülethez.</li>
			<li>Lobby-keresés (publikus lobbyk, privát/jelszavas lobbyk stb.)</li>
			<li>Kalandok közötti importálás lehetősége a KM számára</li>
			<li>Online XML-checker és Editor (nagyobb project)</li>
			<li>Admin karakterlap feltöltés</li>
			<li>Fejlettebb login: OpenID, Facebook, G+, Gravatar</li>
		</ul>
		</p>
		<p>Hubert Viktor - 2012. Április 24.</p>
	</div>
	<div>
		<p>|| Log 02. ||</p>
		<p>Módosítottam jó pár dolgot a kódban, bár ez külsőre csak annyiban jelenik meg, 
		hogy működik a játékba-felkérés, illetve indítható maga a játék is. 
		Ezzel technikailag a lényegi dolgok meg is vannak.</p>
		<div class="title">Technikai infók</div>
		<p>Az oldalak nagy része "page-controller" elven jön be, azaz: 
		http://oldal/fájl.php?változó=érték alakban adom át az értékeket.
		A főoldal egy kicsit ennél egyedibb, a tartalom AJAX-szel töltődik be. 
		(Ezért időnként késhet is a megjelenés.)
		<p>A kódom szintaxisán szépítgettem, igyekeztem következetessé tenni mert a fejlesztés elég ad-hoc alapon 
		működött, illetve munkákból adódóan közben is sokat változott a kódolásom. Ebből adódik pl.: hogy elvileg az
		oldal nagyrésze átállt az úgynevezett '.LESS' stíluslapra sima .css helyett, de helyenként előfordulhatnak sima css szabályok is, 
		illetve a .less fileokban emiatt néhol a struktúra nem a legtisztább logika alapján rendeződik...</p>
		<div class="title">Ami még 'technikai' dolog</div>
		<p>A játékban az Eventeket, karakterlapokat; a főoldalon a felugró hibaüzeneteket jQuery drag&drop ablakokkal fogom megoldani. 
		Az ajax-betöltéseken csiszolok, pl.: ha túl sokáig tölt, loading gif-et jelenítek meg. (karakterlapoknál már ~kb így van.)
		A config.php file-t config.ini-be írom át, belőle asszociatív tömbként kinyerve majd a szügséges adatokat. (Most define()-nal
		deklarálok konstansokat, ami nem rossz, de a .ini elegánsabb. xD) A login-on a regisztrációnál kell még a validáción dolgozni.<br/>
		Ezen kívül pedig még majd ami eszembe jut, amikor a design kerül szóba, illetve a felhasználói kényelem.</p>
		<p>Hubert Viktor - 2012. Január 27.</p>
	</div>
	<div>
		<p>|| Log 01. ||</p>
		<div class="title">Újítások</div>
		<p>Feltüntettem a technológiai részleteket, a főoldalon elhelyeztem a chat-et - ezt mindenki közösen látja -,
		látható az oldal nyelv-váltása, működni viszont még nem fog egy ideig, mert ahhoz még el kell mélyedjek az XML-PHP kettős használatában.<br/>
		A lobby-ban elérhető a spectator mód, kicsit átvariáltam a karakteralkotást is.</p>
		<p>Pénteken résztvettem egy DND-partyn ilyen "spectatorként", szóval annak tapasztalataiból a 3.5-ös DND-t karakterlapját fogom elkészíteni.<br/>
		Az új játék indítását kivettem a bannerből, áttettem a játékokhoz.</p>
		<div class="title">Tervezem</div>
		<p>A kliens-szervert eddig MySQL és Ajax használatával oldottam meg, ezen kicsit változtatok, mert a sok MySQL lekérdezés
		nem elég megbízható. Helyette az egyszerűbb adatokat sima txt-file-okba fogom menteni, ezt sokkal gyorsabban lehet elintézni, mint a
		MySQL adatbázis kapcsolatot, lekérdezést, bezárást.</p>
		<div class="title">Tesztelés</div>
		<p>Sok újdonságot így nem lehet tesztelni, esetleg ezeket megtekinteni különböző böngészőkben.</p>
		<p>Lobby tesztelése úgy lehetséges legegyszerűbben, ha több különböző böngészőbe, külünböző felhasználóval lépünk be.</p>
		Minta-felhasználók:
		<ul>
			<li>mail: test@test.test; pass: test</li>
			<li>mail: control@control.control; pass: control</li>
			<li>mail: hv@hv.hv; pass: hv</li>
		</ul>
		<p>Hubert Viktor - 2011. November 13.</p>
	</div>
</div>