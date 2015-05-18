<!-- Facebook Comments Plugin-->
<!-- 
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_GB/all.js#xfbml=1&appId=401600493241020";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
 -->
<div id="bugs">
	<h2>Fejlesztés</h2><!-- 
	<p class="intro">
		Amíg az oldal tesztfázisban lesz, a Fórum helyett a bugok és fixek kapnak itt helyet.<br/>
		Addig is a Facebook-os csoportba várok minden kérést/észrevételt/panaszt.<br>
		A következő e-mail címen szólhattok hogy felvegyelek a csoportba: <a href="mailto: rpgmorpheus@gmail.com">--jelentkezés: rpgmorpheus@gmail.com--</a>.<br/>
		A csoport bármely tagja hívhat be ismerősöket.
	</p> -->
	<div>
		<div class="bugpage">
		<h3>FIXEK</h3>
        <div class="bugscroll">
			<ul>
				<li class="title">12.10.20 - Loads of stuff</li>
				<li>Jópár apró bug lett javítva.</li>
				<li>A rendszert mostantól a gépen fejlesztem, így csak a stabil verziók fognak felkerülni, elkerülve ezzel a nem észlelt hibákat.</li>
				<li>Új menüpont: validator tool. Ezzel lehet az xml-t tesztelni, mielőtt nekem küldenétek telepítésre. Ahogy a karakterlap fejlődik, úgy fejlődik ez az eszköz is majd.</li>
				<li>A karakterlap 'description' tag-je törölve lett, helyette a leírást egy .ini fájlba kell majd írni. (Részletek majd amikor frissítem a doksit hozzá, egyenlőre ha el van hagyva sem gond.)</li>
                <li>Profil mostmár módosítható. Itt lehet majd még bővebb beállításokat is eszközölni.</li>
                <li>In-game ping tool: mutatja az aktuális ping-et. Ha a zöld téglalapra kattintunk, a szinkronizálás ki lesz kapcsolva, és helyette manuálisan tudjuk frissíteni az oldalt a megjelenő négyzetre kattintva. Ekkor a téglalap szürke lesz, amire való újabb kattintással aktiválhatjuk újra a szinkronizálást. Ez azért készült, mivel a játék nem feltétlen kell valós időben fusson (leszámítva a chat-et, ami nem kapcsol ki ilyenkor sem.), így a gyengébb géppel/nettel rendelkezők is tudnak játszani.</li>
                <li>A Lobby teljesen megszűnt. Helyette a leírás, felhasználók behívása átkerült az in-game felületre. A játékot mostantól meg lehet nézni így akkor is, ha nem te vagy a KM, így ellenőrizheted a karakterlapodat, módosíthatod, olvashatod a bejegyzéseket stb. (Ideiglenesen nincs spectator sem, aki be van hívva, belépéskor automatikusan kap egy üres karakterlapot.)</li>
                <li>A kliens-szerver modellt ismét javítottam, ezzel a technikával ez lesz az utolsó verzió, mert kihoztam belőle amit ki lehet ~kb. Ha ténylegesen valós idejű és szerver-lagg nélküli rendszert akarok csinálni, az már egy komolyabb kérdés saját szerverrel.</li>
			</ul>
			<ul>
				<li class="title">12.08.25 - Lobby/in-game módosítások</li>
				<li>Chrome-ban szétcsúszások bug javítva, újra indíthatóak a játékok.</li>
				<li>Ajax-lekérések időtúllépései javítva.</li>
			</ul>
			<ul>
				<li class="title">12.07.28 - Lobby módosítások</li>
				<li>A lobby átgondoltabban lett összerakva, pár dolog elvileg jobban is működik, de továbbra is a régi kliens-szerver módszert használja.</li>
			</ul>
			<ul>
				<li class="title">12.07.27 - in-game módosítások</li>
				<li>Vizuális javítás az átméretezésekhez, áthelyezésekhez.</li>
				<li>A jobb oldali események helye az ablakmérettől változó, a térkép és bejegyzés helyét a félútra tett elválasztóval lehet módosítani.</li>
			</ul>
			<ul>
				<li class="title">12.07.25 - főoldali módosítások</li>
				<li>A felkérések helye zöldre vált, ha érkezett valami. A barátkereső működésén változtattam.</li>
				<li>Új logolásos rendszer, amivel nyomon követhetem majd az oldalon történteket, statisztikai adatokat gyűjthetek.<br/>
				Egyenlőre csak a belépések időpontjai tároltak, majd tovább bővítem.</li>
			</ul>
			<ul>
				<li class="title">12.07.24 - in-game módosítások</li>
				<li>A bejegyzések frissítései szinkronizáltak a KM és játékosok között.</li>
			</ul>
			<ul>
				<li class="title">12.07.23 - in-game módosítások</li>
				<li>Chat és dobókocka elkészítése</li>
				<li>Az ajax-lekérések hibakezelése lett fejlesztve. If-elágazások helyett Exception, értelmesebb hibaüzenetek.</li>
				<li>A térképeknél több kép is feltölthető, kis ikon generálása mellé. (<strong>Emiatt a módosítás miatt a régi térképek elvesznek.</strong>)</li>
			</ul>
            </div>
		</div>
		<div class="bugpage">
			<h3>BUGOK</h3>
        	<div class="bugscroll">
            	<ul>
            		<li>-</li>
            		<!-- 
                	<li class="title">12.10.02 - karakterlap</li>
                    <li>A regisztrációnál az aktivációs e-mail van ahol nem megfelelően jelenik meg.</li>
                    <li>ingame jobb oldalt a felező-átméretezés görgethető.</li>
                     -->
                </ul>
            </div>
		</div>
	</div>
	<br clear="all" />
    <h3>Tervek</h3>
    <div class="plans">
        <ul>
            <!--<li class="title">12.07.23 - in-game módosítások</li>-->
            <li>Feedback-tool váratlan hibák esetén így a felhasználók jelenthetik a konkrét problémákat, amikor az megtörtént.</li>
            <li>Felületi-súgó, esetleg 'tour' ami elmagyarázza a rendszer viselkedését.</li>
            <li>Felület állíthatósága az opciók menüpontban.</li>
            <li>Játék-kereső, egyúttal játékok jelszavazása/rejtése.</li>
            <li>Intelligensebb barát-kereső, érdeklődési kör alapján is.</li>
            <!-- <li>Üzenetküldés elkészítése.</li> -->
            <li>Login továbbfejlesztése: Facebook, Google+, OpenID. Avatar feltöltése, a php mail-rendszer.</li>
            <li>Statisztikák menüpont.</li>
            <!-- <li>Az elkészült játék ingame módosíthatósága a KM által.</li> -->
            <li>Szöveges inputokhoz (pl: km bejegyzések írása) WYSIWYG editor.</li>
            <li>Fejlettebb dobókocka: a karakterlap alapján. + radnom.org API használata.</li>
            <li>Más is invitálhasson, ha a KM engedélyezi. (Invite-jog adása.))</li>
            <li>ingame témák választása. (sci-fi, fantasy)</li>
            <li>Térképekkel való magasabb szintű interakciók. (pl.: rajzolás/zoomolás) Térképek elnevezésének lehetősége.</li>
        </ul>
	</div>
	<br clear="all" /><!-- 
    <h3>Hozzászólások</h3>
	
	<div style="text-align: center;">
		<div class="fb-comments" data-href="http://rpg.hv-web.hu" data-num-posts="10" data-width="790"></div>
	</div> -->
</div>