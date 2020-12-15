create table lesson(
	id int not null auto_increment
	, primary key (id)
    , place_in_order int
    , name varchar(255)
    , description text
    , introduction text
    , image_path varchar(255)
);

insert into lesson (name, image_path, place_in_order, description) 
	values ("Basic Skills","basic_skills.svg",1,
			"<li>Measuring Ingredients</li><li>Buying Lasting Cookware</li><li>Entertaining and Meal Planning</li>"),
		("Shopping","shopping.svg",2,
			"<li>Buying Fresh Produce</li><li>Navigating the Market</li><li>Discerning Quality</li><li>Storing Your Plunder</li>"),
		("Knife Skills","knife_skills.svg",3,
			"<li>Buying the Knife</li><li>Operating the Knife</li><li>Chopping, Dicing, and Mincing</li><li>Julienning, Slicing</li>"),
		("Eggs","eggs.svg",4,
			"<li>Frying 'em</li><li>Scrambling 'em</li><li>Making 'em Dance</li>"),
		("Salads","salads.svg",5,
			"<li>Choosing Fresh Ingredients</li><li>Balancing Flavors</li><li>Prepping Ahead</li>"),
		("Cocktails","cocktails.svg",6,
			"<li>Shaking</li><li>Stirring</li><li>Culinary and Chemical Balance</li>"),
		("Soups","soups.svg",7,
			"<li>The Ones You Know</li><li>New Friends</li><li>Cold Soups</li>"),
		("Meat","meat.svg",8,
			"<li>The Cuts, The Animals</li><li>The Cooking Methods</li><li>Classic Dishes</li>"),
		("Seafood","seafood.svg",9,
			"<li>Pastas</li><li>Steaming and Lightly Cooking</li><li>Buying Fresh Specimens</li>"),
		("Baking","baking.svg",10,
			"<li>Your First Non-Box Cake</li><li>The Simple</li><li>The Fancy</li>"),
		("Bread","breads.svg",11,
			"<li>Quickbreads</li><li>Long Rises</li><li>Fermentation Nation</li>"),
		("Braising","braising.svg",12,
			"<li>Meat Applications</li><li>Veggie Applications</li><li>The Science</li>"),
		("Roasting","roasting.svg",13,
			"<li>The Grill</li><li>Dry Heat</li><li>Wet Heat</li>"),
		("Stews","stews.svg",14,
			"<li>Classic Stews</li><li>Modern Applications</li><li>Set it and Forget it</li>"),
		("Sauces","sauces.svg",15,
			"<li>Mother Sauces</li><li>Saucing Around the World</li><li>The Foundations of Cuisine</li>"),
		("Frying","frying.svg",16,
			"<li>Shallow Frying</li><li>Deep Frying</li><li>The Science</li>");

create table lesson_detail (
	id int not null auto_increment
	, primary key (id)
    , created_datetime datetime not null default current_timestamp
    , lesson_title text
    , lesson_subtitle text
    , lesson_description text
    , lesson_id int not null
    , foreign key (lesson_id) references lesson (id)
);
update lesson_detail set lesson_description = "In%20the%20beginning%2C%20the%20universe%20was%20created.%20It%20didn%27t%20have%20many%20nice%20restaurants%20and%20you%20couldn%27t%20get%20a%20decent%20knish%2C%20so%20shortly%20after%20the%20beginning%2C%20cooking%20was%20invented.%0A%0AThese%20lessons%20are%20your%20guide%20to%20the%20culinary%20galaxy.%20I%27ll%20be%20your%20lesson%20planner%2C%20spirit%20guide%2C%20and%20occasional%20therapist%20-%20the%20slicing%2C%20stirring%2C%20heating%2C%20and%20eating%20will%20be%20your%20responsibility.%0A%0AIn%20each%20lesson%2C%20you%27ll%20learn%20to%20make%20an%20entire%20dinner%20party%27s%20worth%20of%20new%20dishes%2C%20from%20salad%20and%20soup%20all%20the%20way%20through%20cocktails%20and%20dessert.%20Along%20the%20way%2C%20you%27ll%20pick%20up%20new%20ways%20of%20thinking%2C%20new%20techniques%2C%20and%20new%20recipes%2C%20which%20ascend%20in%20difficulty%20with%20the%20progression%20of%20the%20course%20-%20so%20enjoy%20your%20week%201%20grilled%20cheese%2C%20because%20in%20a%20few%20weeks%20you%27ll%20be%20braising%20short%20ribs%20in%20a%20rich%20wine%20sauce%20to%20serve%20alongside%20creamy%20polenta.%20You%27ll%20cook%20nearly%20a%20hundred%20different%20dishes%20over%20the%20course%20of%20sixteen%20lessons%2C%20and%20your%20learning%20will%20generalize%20so%20that%20you%20can%20cook%20tens%20of%20thousands%20more%20without%20any%20extra%20help.%0A%0AEach%20lesson%20is%20organized%20into%20a%20few%20topics%2C%20which%20are%20meant%20to%20be%20the%20catalysts%20for%20your%20thinking%20-%20they%27re%20brief%20reads%20and%20quizzes%20designed%20to%20get%20you%20excited%20to%20cook%20and%20confident%20you%20can%20do%20it.%20If%20you%20have%20questions%20on%20some%20piece%20of%20content%2C%20if%20you%27d%20like%20to%20see%20something%20added%2C%20or%20if%20something%20just%20doesn%27t%20make%20sense%2C%20%3Ca%20href%3D%22/about%22%3EContact%20me%3C/a%3E%20and%20I%20promise%20that%20I%20will%20personally%20reply.%0A%0AI%20know%20that%20a%20lot%20of%20people%20starting%20off%20as%20cooks%20might%20not%20have%20many%20kitchen%20tools%2C%20so%20I%27ve%20also%20included%20links%20to%20the%20equipment%20you%27re%20going%20to%20need%20for%20each%20recipe%20-%20but%20if%20you%20don%27t%20have%20some%20specific%20device%20or%20if%20the%20list%20just%20looks%20too%20intimidating%20or%20expensive%2C%20you%20can%20usually%20skate%20by%20without%20all%20of%20the%20exact%20equipment%20if%20you%27re%20a%20little%20creative.%20Mark%20the%20completion%20button%20next%20to%20the%20stuff%20you%20have%20and%20the%20recipes%20you%20cook%2C%20and%20RaaSipe%20will%20track%20your%20progress.%0A%0ALike%20all%20great%20arts%2C%20on%20some%20level%20there%27s%20not%20that%20much%20to%20cooking%3B%20it%27s%20essentially%20just%20the%20combination%20and%20temperature%20manipulation%20of%20food.%20Yet%2C%20as%20you%20work%20between%20and%20around%20the%20lines%20of%20what%20you%20know%2C%20you%27ll%20continuously%20uncover%20new%20aspects%20of%20familiar%20flavors%2C%20new%20combinations%20and%20techniques%2C%20and%20new%20ways%20of%20experiencing%20food%20and%20drink.%20Years%20from%20now%20you%20might%20return%20to%20the%20very%20recipes%20you%27ll%20make%20in%20this%20first%20lesson%2C%20bringing%20refined%20experience%20and%20taste%20to%20familiar%20territory%20and%20discovering%20new%2C%20hidden%20aspects%20of%20your%20favorite%20flavors%20-%20that%20way%2C%20you%20can%20continue%20to%20love%20the%20food%20you%20grew%20up%20with%20while%20continually%20expanding%20the%20sphere%20of%20your%20gastronomic%20universe.%0A%0AThis%20is%20the%20most%20fun%20part%20about%20learning%20an%20art%3A%20even%20as%20we%20grow%2C%20change%2C%20and%20multiply%20our%20skills%2C%20there%20will%20always%20be%20some%20new%20dish%20to%20learn%2C%20some%20new%20flavor%20to%20blow%20our%20minds%2C%20and%20someone%20else%20who%20we%20can%20learn%20from.%20Today%2C%20you%27re%20learning%20from%20me%20-%20tomorrow%2C%20you%27ll%20be%20doing%20the%20teaching.%0A%0A"
	where lesson_id = 1;


create table user_lesson_progress (
	user_id int not null
    , lesson_id int not null
    , element_id varchar(255) not null
	, primary key (user_id, lesson_id, element_id)
    , foreign key (user_id) references user (id)
    , completed_datetime datetime not null default current_timestamp
);

create table lesson_topic (
    lesson_id int not null 
    , foreign key (lesson_id) references lesson (id)
    , topic_title text
    , topic_subtitle text
    , place_in_order int
    , text_content text
    , completed_message text
    , primary key (lesson_id,place_in_order)
);

insert into lesson_topic (lesson_id,place_in_order,topic_title,topic_subtitle,text_content) 
		values 
	(1,1,
    "Be the Boss of Mise en Place",
    "Possibly the most important lesson in all of cooking",
    "%27Mise%20en%20place%27%20is%20a%20fancy%20French%20phrase%2C%20but%20don%27t%20worry%3B%20all%20it%20actually%20means%20is%20time%20travel.%0A%0AIf%20you%20are%20among%20those%20humans%20who%20have%20yet%20to%20uncover%20the%20secrets%20of%20personal%20time%20travel%2C%20Mise%20en%20Place%20is%20the%20bargain%20version.%20Imagine%20two%20versions%20of%20yourself%3A%20one%20is%20lazily%20unpacking%20groceries%20in%20the%20early%20afternoon%2C%20maybe%20enjoying%20a%20glass%20of%20prosecco%20because%20it%27s%205%20o%27clock%20somewhere%2C%20and%20chatting%20on%20the%20phone%20with%20an%20old%20friend%20in%20advance%20of%20a%20dinner%20party%20you%27re%20throwing%20that%20evening.%0A%0AYour%20other%20self%20is%20greasy%2C%20angry%2C%20bleeding%2C%20and%20burnt.%20The%20dinner%20party%20is%20well%20underway%2C%20and%20you%27ve%20served%20no%20food.%20Your%20guests%20are%20getting%20hungry%20and%20shiftless%20-%20talk%20of%20a%20Domino%27s%20order%20is%20afoot.%20As%20your%20erstwhile%20friends%20debate%20the%20perpetration%20of%20this%20culinary%20mutiny%2C%20you%20take%20precious%20seconds%20away%20from%20the%20kitchen%20to%20feign%20a%20breezy%20attitude%20and%20mix%20them%20some%20cocktails%20in%20a%20desperate%20gambit%20to%20dull%20their%20awareness%20of%20encroaching%20starvation.%20After%20you%20return%20to%20the%20kitchen%2C%20someone%20produces%20a%20Clif%20bar.%20They%20split%20it.%0A%0AYour%20attention%20spread%20too%20thin%2C%20you%20saut%E9%20the%20onions%20until%20they%20are%20nothing%20more%20than%20a%20black%20hockey%20puck%20at%20the%20bottom%20of%20your%20now-ruined%20cookware.%20While%20you%20are%20slicing%20potatoes%2C%20you%20neglect%20to%20remove%20a%20bottle%20of%20wine%20from%20the%20freezer%2C%20and%20it%20shatters.%20You%20mistake%20coconut%20milk%20for%20heavy%20cream%20and%20serve%20a%20baffling%20potato%20casserole%20about%20which%20your%20friends%20will%20forever%20speak%20in%20hushed%20tones%20the%20way%20survivors%20speak%20of%20hostage%20situations.%0A%0AA%20post-mortem%20reveals%20two%20main%20reasons%20your%20second%20self%20suffered%20this%20savage%20setback.%20One%20is%20time%2C%20and%20the%20other%20is%20attention.%20Fortunately%2C%20both%20of%20these%20are%20available%20for%20purchase%20from%20your%20past%20self%20-%20the%20afternoon%20self%20-%20the%20happy%20self%20-%20via%20the%20technique%20of%20mise%20en%20place.%0A%0AIf%20that%20afternoon%20self%20could%20have%20been%20bothered%20to%20slice%20your%20potatoes%2C%20this%20would%20have%20created%20five%20extra%20minutes%20for%20your%20evening%20self.%20If%20your%20afternoon%20self%20had%20mustered%20the%20wherewithal%20to%20arrange%20the%20liquid%20part%20of%20your%20casserole%20in%20a%20measuring%20pitcher%2C%20you%27d%20have%20had%20no%20hope%20of%20mixing%20up%20the%20ingredients.%20Because%20you%27d%20already%20have%20done%20most%20of%20your%20time-consuming%20chopping%20and%20toasting%20and%20thawing%2C%20and%20because%20you%27d%20already%20have%20done%20most%20of%20your%20attention-consuming%20arranging%2C%20measuring%2C%20and%20organizing%2C%20you%27d%20later%20have%20been%20in%20a%20perfect%20position%20to%20do%20all%20that%20fun%20stuff%20that%20real%20chefs%20do%3A%20tasting%2C%20adjusting%2C%20yelling%20at%20your%20helpers%2C%20and%20graciously%20deflecting%20obsequious%20accolades.%0A%0AMise%20en%20place%2C%20which%20essentially%20means%20%27setup%27%2C%20should%20be%20as%20indispensable%20to%20your%20cooking%20process%20as%20your%20knives%2C%20your%20pots%2C%20and%20your%20heat.%20This%20might%20not%20sound%20like%20that%20big%20of%20a%20deal%20if%20you%27re%20making%2C%20say%2C%20%3Ca%20href%3D%22%23recipe40%22%20class%3D%22link-color%22%20target%3D%22_blank%22%3EChai%20Masala%3C/a%3E%2C%20but%20if%20you%27re%20making%20%3Ca%20href%3D%22%23recipe13%22%20class%3D%22link-color%22%20target%3D%22_blank%22%3EBaked%20Apples%3C/a%3E%2C%20a%20%3Ca%20href%3D%22%23recipe38%22%20class%3D%22link-color%22%20target%3D%22_blank%22%3ECaesar%20Salad%3C/a%3E%2C%20or%20%3Ca%20href%3D%22%23recipe85%22%20class%3D%22link-color%22%20target%3D%22_blank%22%3ETuscan%20Beef%20Stew%3C/a%3E%2C%20mise%20en%20place%20can%20mean%20the%20difference%20between%20delicious%20and%20defeated.%0A%0AAfter%20all%2C%20mise%20en%20place%20is%20a%20deep%20acknowledgement%20of%20the%20nature%20of%20time%3A%20you%20can%20control%20when%20you%20turn%20on%20the%20heat%2C%20but%20you%20can%27t%20control%20how%20fast%20that%20heat%20will%20burn%20onions.%20You%20can%20control%20when%20you%20start%20whipping%20egg%20whites%2C%20but%20you%20can%27t%20control%20when%20overwhipped%20egg%20whites%20will%20collapse.%20By%20arranging%20everything%20that%27s%20not%20time-sensitive%20before%20the%20time-sensitive%20stuff%20starts%20to%20happen%2C%20you%20foolproof%20your%20cooking%20process%20-%20this%20means%20that%20you%27ll%20be%20free%20to%20focus%20on%20the%20art%20of%20cooking%2C%20because%20you%20can%20be%20confident%20that%20your%20inner%20culinary%20voice%20won%27t%20be%20shouted%20down%20by%20circumstance.%0A%0AIf%20there%20are%20multiple%20universes%2C%20then%20maybe%20in%20one%20of%20them%2C%20the%20French%20won%20the%20Napoleonic%20wars.%20Perhaps%20there%2C%20evening%20selves%20like%20yours%20don%27t%20exist%20because%20mise%20en%20place%20is%20as%20mandatory%20as%20car%20insurance.%20In%20this%20universe%2C%20because%20our%20government%20refuses%20to%20step%20in%20%28despite%20my%20strongly-worded%20letters%29%2C%20we%20will%20have%20to%20take%20matters%20into%20our%20own%20hands%20-%20so%20skip%20the%20advanced%20physics%20degree%20and%20make%20time%20travel%20a%20reality%20in%20your%20kitchen%20with%20mise%20en%20place."
    ),(
    1,2,
    "Getting a Taste for it",
    "The secret weapon *they* don't want you to know",
    ""
    ),(
    1,3,
    "The Measure of the Man (or Woman)",
    "Why weights and measures aren't just a matter for the House of Commons",
    "");