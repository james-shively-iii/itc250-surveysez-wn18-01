<?php
/**
 * survey_view.php provides a List/View 
 * application for the SurveySez project
 *
 * @package SurveySez
 * @author James Shively <james.shively-iii@seattlecentral.edu>
 * @version 0.1 
 * @link http://www.reedly.info/itc250/
 * @license https://www.apache.org/licenses/LICENSE-2.0
 * @see survey_list.php
 * @todo none
 */

# '../' works for a sub-folder.  use './' for the root  
require '../inc_0700/config_inc.php'; #provides configuration, pathing, error handling, db credentials
 
# check variable of item passed in - if invalid data, forcibly redirect back to demo_list.php page
if(isset($_GET['id']) && (int)$_GET['id'] > 0){#proper data must be on querystring
	 $myID = (int)$_GET['id']; #Convert to integer, will equate to zero if fails
}else{
	myRedirect(VIRTUAL_PATH . "survey/index.php");
}

$mySurvey = new Survey($myID);

//sql statement to select individual item
//$sql = "select Title, Description,DateAdded from wn18_surveys where SurveyID = " . $myID;
//---end config area --------------------------------------------------
/*
$foundRecord = false; # Will change to true, if record found!
   
# connection comes first in mysqli (improved) function
$result = mysqli_query(IDB::conn(),$sql) or die(trigger_error(mysqli_error(IDB::conn()), E_USER_ERROR));

if(mysqli_num_rows($result) > 0)
{#records exist - process
	   $foundRecord = true;	
	   while ($row = mysqli_fetch_assoc($result))
	   {
			$Title = dbOut($row['Title']);
			$Description = dbOut($row['Description']);
			$DateAdded = dbOut($row['DateAdded']);
	   }
}

@mysqli_free_result($result); # We're done with the data!
*/
if($mySurvey->IsValid)
{#only load data if record found
	$config->titleTag = $mySurvey->Title; #overwrite PageTitle with Muffin info!
}
/*
$config->metaDescription = 'Web Database ITC281 class website.'; #Fills <meta> tags.
$config->metaKeywords = 'SCCC,Seattle Central,ITC281,database,mysql,php';
$config->metaRobots = 'no index, no follow';
$config->loadhead = ''; #load page specific JS
$config->banner = ''; #goes inside header
$config->copyright = ''; #goes inside footer
$config->sidebar1 = ''; #goes inside left side of page
$config->sidebar2 = ''; #goes inside right side of page
$config->nav1["page.php"] = "New Page!"; #add a new page to end of nav1 (viewable this page only)!!
$config->nav1 = array("page.php"=>"New Page!") + $config->nav1; #add a new page to beginning of nav1 (viewable this page only)!!
*/
# END CONFIG AREA ---------------------------------------------------------- 

get_header(); #defaults to theme header or header_inc.php
?>
<?php
if($mySurvey->IsValid)
{#records exist - show survey!
 echo '
 <h3 align="center">' . $mySurvey->Title . '</h3>
 <p>Description: ' . $mySurvey->Description . '</p>
 <p>Date Added: ' . $mySurvey->DateAdded . '</p>
 ';
}else{//no such survey!
    echo '
    <p>There is no such survey</p>
    ';
}

get_footer(); #defaults to theme footer or footer_inc.php

class Survey
{//start Survey class
    
    public $SurveyID = 0;
    public $Title = '';
    public $Description = '';
    public $DateAdded = '';
    public $IsValid = false;

    public function __contruct($myID)
    {//start Survey constructor
        
        //cast the data to an integer
        $this->SurveyID = (int)$myID;
        
        $sql = "select Title, Description,DateAdded from wn18_surveys where SurveyID = " . $myID;
        
        # connection comes first in mysqli (improved) function
        $result = mysqli_query(IDB::conn(),$sql) or die(trigger_error(mysqli_error(IDB::conn()), E_USER_ERROR));

        if(mysqli_num_rows($result) > 0)
        {#records exist - process
               $this->IsValid = true;	
               while ($row = mysqli_fetch_assoc($result))
               {
                    $this->Title = dbOut($row['Title']);
                    $this->Description = dbOut($row['Description']);
                    $this->DateAdded = dbOut($row['DateAdded']);
               }
        }

        @mysqli_free_result($result); # We're done with the data!
        
    }//end Survey constructor
}//end Survey class






