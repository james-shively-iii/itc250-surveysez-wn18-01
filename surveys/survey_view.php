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
	myRedirect(VIRTUAL_PATH . "surveys/index.php");
}

$mySurvey = new Survey($myID);

dumpDie($mySurvey);

//---end config area --------------------------------------------------

if($mySurvey->IsValid)
{#only load data if record found
	$config->titleTag = $mySurvey->Title; #overwrite PageTitle with Muffin info!
}

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
{
    public $SurveyID = 0;
    public $Title = '';
    public $Description = '';
    public $DateAdded = '';
    public $IsValid = false;
    public $Questions = array();
    
    public function __construct($myID)
    {
        //cast the data to an integer
        $this->SurveyID = (int)$myID;
        
        $sql = "select Title,Description,DateAdded from wn18_surveys where SurveyID = " . $this->SurveyID;
   
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
        
        /*  start question class here */

        //Select QuestionID, Question, Description from wn18_questions where SurveyID = 1
        
        $sql = "Select QuestionID, Question, Description from wn18_questions where SurveyID = " . $this->SurveyID;
   
        # connection comes first in mysqli (improved) function
        $result = mysqli_query(IDB::conn(),$sql) or die(trigger_error(mysqli_error(IDB::conn()), E_USER_ERROR));

        if(mysqli_num_rows($result) > 0)
        {#records exist - process
               while ($row = mysqli_fetch_assoc($result))
               {
                    /*
                    $this->Title = dbOut($row['Title']);
                    $this->Description = dbOut($row['Description']);
                    $this->DateAdded = dbOut($row['DateAdded']);
                    */
                   $this->Questions[] = new Question(dbOut($row['QuestionID']),dbOut($row['Question']),dbOut($row['Description']));
                   
               }
        }

        @mysqli_free_result($result); # We're done with the data!
        
        
        
        
        /* end question class here */
    }//end Survey constructor
    
}//end Survey class

class Question{//start Question Class
    
    public $QuestionID = 0;
    public $QuestionText = '';
    public $Description = '';
    
    public function __construct($QuestionID,$QuestionText,$Description)
    {//start Question constructor
        $this->QuestionID = $QuestionID;
        $this->QuestionText = $QuestionText;
        $this->Description = $Description;
        
    }//end Question constructor
    
    
}//end Question Class