<?php
/**
* @package My Remote Tube Video Gallery
* @copyright Copyright (c)2009 Subfighter Developers
* @license GNU General Public License version 2, or later
* @version 1.0.0
* @since 1.0
*/

defined('_JEXEC') or die('Restricted access');
?>
<?php
    echo $this->pane->startPane( 'stat-pane' );

    /*Seyret tab*/
    $title = JText::_( 'Seyret' );
    echo $this->pane->startPanel( $title, 'seyret' );
    require_once($this->tmplpath . DS . 'seyret.php');
    echo $this->pane->endPanel();

    /*HWDVideoShare tab*/
    $title = JText::_('HWDVideoShare ');
    echo $this->pane->startPanel( $title, 'hwd' );
    require_once($this->tmplpath . DS . 'hwd.php');
    echo $this->pane->endPanel();

?>