<?php
/**
 * Created by PhpStorm.
 * User: Joseph
 * Date: 19/05/2018
 * Time: 15:39
 */

/**
 * Class HtmlPageAbstract
 */
abstract class HtmlPageAbstract
{
    /**
     * @var string
     */
    private $VIEW_FILE_PATH = '../../src/views/';

    /**
     * @var string
     */
    private $VIEW_HEADER_FILE_PATH = '../../src/headers/viewHeader.php';

    /**
     * @var string
     */
    private $HTML_PAGES_PATH = '../pageusr/';

    /**
     * @var string
     */
    private $REQUIRE_COMAND = 'include_once __DIR__ . "/../views/';

    /**
     * @var
     */
    protected $systemPlugins;
    /**
     * @var
     */
    protected $linkKey;
    /**
     * @var
     */
    protected $pageName;
    /**
     * @var
     */
    protected $title;
    /**
     * @var
     */
    protected $funcname;
    /**
     * @var
     */
    protected $view;
    /**
     * @var
     */
    protected $header;

    /**
     * @var array
     */
    public $catTabsLinks = [];

    /**
     * @var array
     */
    public $navTabsLinks = [];

    /**
     * @var array
     */
    public $pages = [
        'home' =>
            [
                'name' => 'home',
                'focus' => false,
                'title' => 'Welcome to the Homepage',
                'filename' => 'home.php',
                'funcname' => 'homeView',
                'viewname' => 'homeView.php',
                'cattabsname' => 'Home',
                'showcattab' => true,
                'shownavtab' => false,
                'showsidebar' => false,
                'header' => 'DEFAULT HOME PAGE HEADER',
                'footer' => null,
            ],
        'page1' =>
            [
                'name' => 'page1',
                'focus' => false,
                'title' => 'Welcome to Page One',
                'filename' => 'page1.php',
                'funcname' => 'page1View',
                'viewname' => 'page1View.php',
                'cattabsname' => 'Page 1',
                'showcattab' => true,
                'shownavtab' => false,
                'showsidebar' => false,
                'header' => 'DEFAULT PAGE ONE HEADER',
                'footer' => null,
            ],
        'page2' =>
            [
                'name' => 'page2',
                'focus' => false,
                'title' => 'Welcome to Page Two',
                'filename' => 'page2.php',
                'funcname' => 'page2View',
                'viewname' => 'page2View.php',
                'cattabsname' => 'Page 2',
                'showcattab' => true,
                'shownavtab' => false,
                'showsidebar' => false,
                'header' => 'DEFAULT PAGE TWO HEADER',
                'footer' => null,
            ],
        'page3' =>
            [
                'name' => 'page3',
                'focus' => false,
                'title' => 'Welcome to Page Three',
                'filename' => 'page3.php',
                'funcname' => 'page3View',
                'viewname' => 'page3View.php',
                'cattabsname' => 'Page 3',
                'showcattab' => true,
                'shownavtab' => false,
                'showsidebar' => false,
                'header' => 'DEFAULT PAGE THREE HEADER',
                'footer' => null,
            ],
    ];

    /**
     * @param $pageFocus
     */
    protected function setHtmlPage($pageFocus)
    {
        $this->systemPlugins = new TagRenderer();

        foreach ($this->pages as $pageName => $v) {

            foreach ($this->pages[$pageName] as $property => $w) {

                /* Exclusive to the HTML Page in focus */
                if ($property == 'name') {
                    if ($this->pages[$pageName][$property] == $pageFocus) {

                        $this->pages[$pageName]['focus'] = true;
                        $this->pageName = $this->pages[$pageName]['name'];
                        $this->title = $this->pages[$pageName]['title'];
                        $this->funcname = $this->pages[$pageName]['funcname'];
                        $this->view = $this->pages[$pageName]['viewname'];
                        $this->header = $this->pages[$pageName]['header'];
                        $this->linkKey = $this->pages[$pageName]['filename'];

                    } else {

                        $this->pages[$pageName]['focus'] = false;
                    }
                }

                /* Project wide */
                if ($property == 'cattabsname') {

                    if($this->pages[$pageName]['showcattab']) {

                        $catKey = $this->pages[$pageName]['filename'];
                        $catVal = $this->pages[$pageName]['cattabsname'];

                        $this->catTabsLinks[] = [$catKey => $catVal];
                    }

                    if($this->pages[$pageName]['shownavtab']) {

                        $catKey = $this->pages[$pageName]['filename'];
                        $catVal = $this->pages[$pageName]['cattabsname'];

                        $this->navTabsLinks[] = [$catKey => $catVal];
                    }
                }

                if ($property == 'viewname') {

                    $fileName = $this->pages[$pageName][$property];
                    $func = $this->pages[$pageName]['funcname'];
                    $fileLocation = $this->VIEW_FILE_PATH . $fileName;

                    if (!file_exists($fileLocation)) {

                        /* 1/3 GENERATE PAGE VIEW */
                        if (!is_file($fileName)) {
                            /* Page Construction */
                            $newViewPageBuild = "                           
                               <?php
                                function $func()
                                {
                                    ?>
                                    <!-- This is where you put your HTML -->
                                                                    
                                    <?php
                                    /** This is where you put your PHP  **/
                                                                                            
                                    echo \"THIS IS THE {$this->pages[$pageName]['name']} VIEW\";                                                                                                                
                                }                                        
                            ";
                            file_put_contents($fileLocation, $newViewPageBuild);
                        }

                        /* 2/3 BUILDER HEADER & INSERT VIEW HEADER STRING */
                        $headerCommand = $this->REQUIRE_COMAND . $this->pages[$pageName]['viewname']. '";';
                        file_put_contents($this->VIEW_HEADER_FILE_PATH, $headerCommand.PHP_EOL , FILE_APPEND | LOCK_EX);

                        /* 3/3 BUILD HTML PAGE */
                        $fileName = $this->pages[$pageName]['filename'];
                        $fileLocation = $this->HTML_PAGES_PATH . $fileName;

                        if (!file_exists($fileLocation)) {
                            $htmlPageName = $this->pages[$pageName]['name'];
                            $newHtmlPageBuild = '                                  
                            <?php
                            session_start();
                            require_once __DIR__ . "/../../src/headers/systemHeader.php";
                            error_reporting(E_ALL); ini_set(\'display_errors\', \'1\');
                            $htmlPage = new HtmlPageRenderer();
                            $htmlPage->render("';
                              $newHtmlPageBuild .= $htmlPageName;
                              $newHtmlPageBuild .= '");                                                                                                                                                                             
                            ';
                            file_put_contents($fileLocation, $newHtmlPageBuild);
                        }
                        else {
                            //      FILE DOES EXITS... CLEAN UP?";
                        }/* END PROTOTYPE */
                    } /* END CHECK FILE EXISTENCE */
                } /* END 'viewname' */
            } /* End For Each - Value */
        } /* End For Each - Key */
    } /* End Function */
}
