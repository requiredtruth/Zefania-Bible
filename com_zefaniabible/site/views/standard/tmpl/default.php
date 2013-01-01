<?php
/**                               ______________________________________________
*                          o O   |                                              |
*                 (((((  o      <  Generated with Cook           (100% Vitamin) |
*                ( o o )         |______________________________________________|
* --------oOOO-----(_)-----OOOo---------------------------------- www.j-cook.pro --- +
* @version		1.6
* @package		ZefaniaBible
* @subpackage	Zefaniabible
* @copyright	Missionary Church of Grace
* @author		Andrei Chernyshev - www.missionarychurchofgrace.org - andrei.chernyshev1@gmail.com
* @license		GNU/GPL
*
* /!\  Joomla! is free software.
* This version may have been modified pursuant to the GNU General Public License,
* and as distributed it includes or is derivative of works licensed under the
* GNU General Public License or other free or open source software licenses.
*
*             .oooO  Oooo.     See COPYRIGHT.php for copyright notices and details.
*             (   )  (   )
* -------------\ (----) /----------------------------------------------------------- +
*               \_)  (_/
*/

defined('_JEXEC') or die('Restricted access'); ?>
<?php ZefaniabibleHelper::headerDeclarations(); ?>
<?php 
JHTML::_('behavior.modal');
$cls_bibleBook = new BibleStandard($this->arr_Chapter, $this->arr_Bibles, $this->str_Bible_Version, $this->int_Bible_Book_ID, $this->int_Bible_Chapter); 

class BibleStandard {
	public $obj_Bible_Dropdown;
	public $obj_Book_Dropdown;
	public $int_Bibles_loaded;
	public $str_Chapter_Output;
	public $flg_show_credit;
	public $flg_show_page_top;
	public $flg_show_page_bot;
	public $flg_show_pagination_type;
	public $flg_email_button;
	public $flg_show_audio_player;
	public $int_player_popup_height;
	public $int_player_popup_width;
	
	public function __construct($arr_Chapter, $arr_Bibles, $str_Bible_Version, $int_Bible_Book_ID, $int_Bible_Chapter)
	{
		$this->params = &JComponentHelper::getParams( 'com_zefaniabible' );
		$this->doc_page =& JFactory::getDocument();	
		$this->flg_show_page_top 	= $this->params->get('show_pagination_top', '1');
		$this->flg_show_page_bot 	= $this->params->get('show_pagination_bot', '1');	
		$this->flg_email_button 	= $this->params->get('flg_email_button', '1');	
		
		$this->flg_show_credit 		= $this->params->get('show_credit','0');
		$this->flg_show_pagination_type = $this->params->get('show_pagination_type','0');
		$this->flg_show_audio_player = $this->params->get('show_audioPlayer','0');
		$this->int_player_popup_height = $this->params->get('player_popup_height','300');
		$this->int_player_popup_width = $this->params->get('player_popup_width','300');
		$obj_Bible_Dropdown = '';
		$str_Chapter_Output = '';
		$obj_Book_Dropdown = '';
		$int_Bibles_loaded = 0;
		$x = 1;
		$str_descr = '';
		$str_alias = '';
				
		foreach($arr_Bibles as $str_Bible)
		{
			if($str_Bible_Version == $str_Bible->alias)
			{
				$this->obj_Bible_Dropdown = $this->obj_Bible_Dropdown.'<option value="'.$str_Bible->alias.'" selected>'.$str_Bible->bible_name.'</option>';
				$str_alias = $str_Bible->alias;
			}
			else
			{
				$this->obj_Bible_Dropdown = $this->obj_Bible_Dropdown.'<option value="'.$str_Bible->alias.'" >'.$str_Bible->bible_name.'</option>';
			}
			$this->int_Bibles_loaded++;
		}
		
		for($x = 1; $x <=66; $x++)
		{
			if($int_Bible_Book_ID == $x)
			{
				$this->obj_Book_Dropdown = $this->obj_Book_Dropdown. '<option value="'.$x."-".mb_strtolower(str_replace(" ","-",JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$x)),'UTF-8').'" selected>'.JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$x).'</option>';						
			}
			else
			{
				$this->obj_Book_Dropdown = $this->obj_Book_Dropdown. '<option value="'.$x."-".mb_strtolower(str_replace(" ","-",JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$x)),'UTF-8').'" >'.JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$x).'</option>';				
			}
		}
		foreach ($arr_Chapter as $arr_verse)
		{
			if($arr_verse->verse_id <= 2)
			{
				$str_descr = $str_descr." ".trim($arr_verse->verse);	
			}
			if ($x % 2)
			{
				$this->str_Chapter_Output  = $this->str_Chapter_Output.'<div class="odd">';
			}
			else
			{
				$this->str_Chapter_Output  = $this->str_Chapter_Output.'<div class="even">'; 
			}	
			$this->str_Chapter_Output  = $this->str_Chapter_Output."<div class='zef_verse_number'>".$arr_verse->verse_id."</div><div class='zef_verse'>".$arr_verse->verse."</div>";
			$this->str_Chapter_Output  = $this->str_Chapter_Output.'<div style="clear:both"></div></div>';
			$x++;
		}
		$this->fnc_meta_data($int_Bible_Book_ID, $int_Bible_Chapter,$str_descr,$str_alias);
		
	}
	private function fnc_meta_data($int_Bible_Book_ID, $int_Bible_Chapter,$str_descr,$str_alias)
	{
		$str_descr = trim(mb_substr($str_descr,0,146))." ...";
		$app_site = JFactory::getApplication();
		$str_title = JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$int_Bible_Book_ID)." ".mb_strtolower(JText::_('ZEFANIABIBLE_BIBLE_CHAPTER'),'UTF-8')." ".$int_Bible_Chapter.' - '.$str_alias;
		$this->doc_page->setMetaData( 'description', $str_descr);
		$this->doc_page->setMetaData( 'keywords', $str_title.",".$str_alias );
		$this->doc_page->setTitle($str_title);
					
		// Facebook Open Graph
		$this->doc_page->setMetaData( 'og:title', $str_title);
		$this->doc_page->setMetaData( 'og:url', JFactory::getURI()->toString());		
		$this->doc_page->setMetaData( 'og:type', "article" );	
		$this->doc_page->setMetaData( 'og:image', JURI::root()."components/com_zefaniabible/images/bible_100.jpg" );	
		$this->doc_page->setMetaData( 'og:description', $str_descr );
		$this->doc_page->setMetaData( 'og:site_name', $app_site->getCfg('sitename') );			
	}
	
	public function fnc_Pagination_Buttons($str_Bible_Version, $int_Bible_Book_ID, $int_Bible_Chapter, $int_max_chapter)
	{	
		$urlPrepend = "document.location.href=('";
		$urlPostpend = "')";
	
		if($int_Bible_Book_ID > 1)
		{
			$url[3] = JRoute::_("index.php?option=com_zefaniabible&a=".$str_Bible_Version."&view=".JRequest::getCmd('view')."&b=".
			($int_Bible_Book_ID-1)."-".str_replace(" ","-",mb_strtolower(JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.
			($int_Bible_Book_ID-1),'UTF-8')))."&c=1-".mb_strtolower(JText::_('ZEFANIABIBLE_BIBLE_CHAPTER'),'UTF-8'));
			if($this->flg_show_pagination_type == 0)
			{
				echo '<input title="'.JText::_('ZEFANIABIBLE_BIBLE_LAST_BOOK').'" type="button" id="zef_Buttons" class="zef_lastBook" name="lastBook" onclick="'.$urlPrepend.$url[3].$urlPostpend.'"  value="'. JText::_("ZEFANIABIBLE_BIBLE_BOOK_NAME_".($int_Bible_Book_ID-1)).' 1"'.' />';
			}
			else
			{
				echo "<a title='".JText::_('ZEFANIABIBLE_BIBLE_LAST_BOOK')."' id='zef_links' href='".$url[3]."'>".JText::_("ZEFANIABIBLE_BIBLE_BOOK_NAME_".($int_Bible_Book_ID-1)).' 1'."</a> ";
			}
		}
		if($int_Bible_Chapter > 1)
		{
			$url[1] = JRoute::_("index.php?option=com_zefaniabible&a=".$str_Bible_Version."&view=".JRequest::getCmd('view')."&b=".
			$int_Bible_Book_ID."-".str_replace(" ","-",mb_strtolower(JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$int_Bible_Book_ID,'UTF-8')))."&c=".($int_Bible_Chapter-1).
			"-".mb_strtolower(JText::_('ZEFANIABIBLE_BIBLE_CHAPTER'),'UTF-8'));		
			if($this->flg_show_pagination_type == 0)
			{
				echo '<input title="'.JText::_('ZEFANIABIBLE_BIBLE_LAST_CHAPTER').'" type="button" id="zef_Buttons" class="zef_lastChapter" name="lastChapter" onclick="'.$urlPrepend.$url[1].$urlPostpend.'"  value="'. JText::_("ZEFANIABIBLE_BIBLE_BOOK_NAME_".$int_Bible_Book_ID)." ".($int_Bible_Chapter-1).'" />';
			}
			else
			{
				echo "<a title='".JText::_('ZEFANIABIBLE_BIBLE_LAST_CHAPTER')."' id='zef_links' href='".$url[1]."'>".JText::_("ZEFANIABIBLE_BIBLE_BOOK_NAME_".$int_Bible_Book_ID).' '.($int_Bible_Chapter-1)."</a> ";
			}
		}
		if($int_Bible_Chapter < $int_max_chapter)
		{
			$url[0] = JRoute::_("index.php?option=com_zefaniabible&a=".$str_Bible_Version."&view=".
			JRequest::getCmd('view')."&b=".$int_Bible_Book_ID."-".str_replace(" ","-",mb_strtolower(JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$int_Bible_Book_ID),'UTF-8'))."&c=".
			($int_Bible_Chapter+1)."-".mb_strtolower(JText::_('ZEFANIABIBLE_BIBLE_CHAPTER'),'UTF-8'));		
			if($this->flg_show_pagination_type == 0)
			{			
				echo '<input title="'.JText::_('ZEFANIABIBLE_BIBLE_NEXT_CHAPTER').'" type="button" id="zef_Buttons" class="zef_NextChapter" name="nextChapter" onclick="'.$urlPrepend.$url[0].$urlPostpend.'"  value="'. JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$int_Bible_Book_ID)." ".($int_Bible_Chapter+1).'" />';
			}
			else
			{
				echo "<a title='".JText::_('ZEFANIABIBLE_BIBLE_NEXT_CHAPTER')."' id='zef_links' href='".$url[0]."'>".JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$int_Bible_Book_ID).' '.($int_Bible_Chapter+1)."</a> ";
			}
		}
		if($int_Bible_Book_ID < 66)
		{
			$url[2] = JRoute::_("index.php?option=com_zefaniabible&a=".$str_Bible_Version."&view=".JRequest::getCmd('view')."&b=".
			($int_Bible_Book_ID+1)."-".str_replace(" ","-",mb_strtolower(JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.
			($int_Bible_Book_ID+1),'UTF-8')))."&c=1-".mb_strtolower(JText::_('ZEFANIABIBLE_BIBLE_CHAPTER'),'UTF-8'));
			if($this->flg_show_pagination_type == 0)
			{			
				echo '<input title="'.JText::_('ZEFANIABIBLE_BIBLE_NEXT_BOOK').'" type="button" id="zef_Buttons" class="zef_NextBook" name="nextBook" onclick="'.$urlPrepend.$url[2].$urlPostpend.'"  value="'. JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.($int_Bible_Book_ID+1)).' 1"'.' />';
			}
			else
			{
				echo "<a title='".JText::_('ZEFANIABIBLE_BIBLE_NEXT_BOOK')."' id='zef_links' href='".$url[2]."'>". JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.($int_Bible_Book_ID+1)).' 1'."</a>";
			} 
		}
	}	
}
?>

<form action="<?php echo JFactory::getURI()->toString(); ?>" method="post" id="adminForm" name="adminForm">
	<div id="zef_Bible_Main">
    	<div class="zef_legend">
        	<?php if($cls_bibleBook->flg_email_button){?>
            <div class="zef_email_button"><a title="<?php echo JText::_('ZEFANIABIBLE_EMAIL_BUTTON_TITLE'); ?>" target="blank" href="index.php?view=subscribe&option=com_zefaniabible&tmpl=component" class="modal" rel="{handler: 'iframe', size: {x:500,y:400}}" ><img class="zef_email_img" src="<?php echo JURI::root()."components/com_zefaniabible/images/e_mail.png"; ?>" /></a></div>
            <?php } ?>
            <div class="zef_bible_Header_Label"><?php echo JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$this->int_Bible_Book_ID)." ".mb_strtolower(JText::_('ZEFANIABIBLE_BIBLE_CHAPTER'),'UTF-8')." ".$this->int_Bible_Chapter; ?></div>
            <?php if($cls_bibleBook->int_Bibles_loaded > 1){?>
                <div class="zef_bible_label"><?php echo JText::_('ZEFANIABIBLE_BIBLE_VERSION');?></div>
                <div class="zef_bible">
                    <select name="a" id="bible" class="inputbox" onchange="this.form.submit()">
                        <?php echo $cls_bibleBook->obj_Bible_Dropdown; ?>
                    </select>
                </div>
            <?php }else{
					echo '<input type="hidden" name="a" value="'.$this->str_Bible_Version.'" />';
				}
				?>  
            <div class="zef_book_label"><?php echo JText::_('ZEFANIABIBLE_BIBLE_BOOK');?></div>
            <div class="zef_book">
                <select name="b" id="book" class="inputbox" onchange="this.form.submit()">
					<?php 
						echo $cls_bibleBook->obj_Book_Dropdown;
					?>
                </select>
            </div>
            <div class="zef_chapter_label"><?php echo JText::_('ZEFANIABIBLE_BIBLE_CHAPTER');?></div>
            <div class="zef_Chapter">
                <select name="c" id="chapter" class="inputbox" onchange="this.form.submit()">
					<?php 
						for( $x = 1; $x <= $this->int_max_chapter; $x++)
						{
							if($x == $this->int_Bible_Chapter)
							{
								echo '<option value="'.$x.'-'.mb_strtolower(JText::_('ZEFANIABIBLE_BIBLE_CHAPTER'),'UTF-8').'" selected="selected">'.$x.'</option>';
							}
							else
							{
								echo '<option value="'.$x.'-'.mb_strtolower(JText::_('ZEFANIABIBLE_BIBLE_CHAPTER'),'UTF-8').'">'.$x.'</option>';
							}
						}
					?>               
                </select>
            </div>
            <div style="clear:both;"></div>
            <div class="zef_top_pagination">
         		<?php if($cls_bibleBook->flg_show_page_top){ $cls_bibleBook->fnc_Pagination_Buttons($this->str_Bible_Version,$this->int_Bible_Book_ID, $this->int_Bible_Chapter, $this->int_max_chapter);} ?>
            </div> 
        </div>
        <?php if($cls_bibleBook->flg_show_audio_player){ ?>
         <div class="zef_player">
        	<?php echo $this->obj_player;
			echo '<div style="clear:both;"></div>';
            echo  '<a href="#" onclick="return popitup(\''.JURI::root().'index.php?option=com_zefaniabible&a='.$this->str_Bible_Version.'&view=player&tmpl=component&b='.$this->int_Bible_Book_ID.'\')" target="_blank" >'.JText::_('ZEFANIABIBLE_PLAYER_WHOLE_BOOK')."</a>";
			 ?>
        </div>
        <?php }?>
        <div style="clear:both;"></div>
        <div class="zef_bible_Chapter"><?php echo $cls_bibleBook->str_Chapter_Output; ?></div>     
        <div class="zef_footer">
            <div class="zef_bot_pagination">
            	<?php if($cls_bibleBook->flg_show_page_bot){ $cls_bibleBook->fnc_Pagination_Buttons($this->str_Bible_Version,$this->int_Bible_Book_ID, $this->int_Bible_Chapter, $this->int_max_chapter);} ?>        
				<div style="clear:both;"></div>
				<?php if($cls_bibleBook->flg_show_credit){ echo JText::_('ZEFANIABIBLE_DEVELOPED_BY')." <a href='http://www.zefaniabible.com/' target='_blank'>Zefania Bible</a>"; } ?>
            </div>  
        </div>
    </div>
	<input type="hidden" name="option" value="<?php echo JRequest::getCmd('option');?>" />
	<input type="hidden" name="view" value="<?php echo JRequest::getCmd('view');?>" />
    <input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid'); ?>"/>
</form>
<script language="javascript" type="text/javascript">
function popitup(url) {
	newwindow=window.open(url,'name','height=<?php echo ($cls_bibleBook->int_player_popup_height);?>,width=<?php echo ($cls_bibleBook->int_player_popup_width);?>','scrollbars=no','resizable=no');
	if (window.focus) {newwindow.focus()}
	return false;
}
</script>