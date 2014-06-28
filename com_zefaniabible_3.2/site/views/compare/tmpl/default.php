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
<?php 
$cls_bibleBook = new BibleCompare($this->arr_Chapter, $this->arr_Bibles, $this->str_Bible_Version, $this->int_Bible_Book_ID, $this->str_Bible_Version2, $this->arr_Chapter2, $this->int_Bible_Chapter,$this->arr_commentary, $this->obj_references, $this->arr_commentary_list, $this->arr_dictionary_list); 

class BibleCompare {
	public $obj_Bible_Dropdown;
	public $obj_Bible_Dropdown2;	
	public $obj_Book_Dropdown;
	public $int_Bibles_loaded;
	public $str_Chapter_Output;
	public $flg_show_credit;
	public $flg_show_page_top;
	public $flg_show_page_bot;
	public $flg_show_pagination_type;
	private $doc_page;
	public $flg_email_button;
	public $flg_show_audio_player;
	public $int_player_popup_height;
	public $int_player_popup_width;
	public $flg_show_second_player;
	public $flg_use_bible_selection;
	public $flg_show_commentary;
	private $str_commentary;
	public $flg_show_references;
	public $str_commentary_width;
	public $str_commentary_height;	
	private $str_dictionary_height;
	private $str_dictionary_width;
	public $str_primary_dictionary;
	public $flg_show_dictionary;
	public $str_primary_commentary;
	public $str_tmpl;
	public $flg_strong_dict;
	private $arr_commentary_list;
	private $arr_dictionary_lis;
	public $arr_english_book_names;
	public $str_default_image;
		
	public function __construct($arr_Chapter, $arr_Bibles, $str_Bible_Version, $int_Bible_Book_ID, $str_Bible_Version2, $arr_Chapter2, $int_Bible_Chapter,$arr_commentary, $arr_references, $arr_commentary_list, $arr_dictionary_list)
	{
		/*
			Standard Bible
			a = bible
			b = bible2
			c = book
			d = chapter
			com = commentary
			dict = Dictionary
			strong = Show/Hide Strong Numgers flag		
		*/			
		$this->params = JComponentHelper::getParams( 'com_zefaniabible' );
		$this->doc_page = JFactory::getDocument();	
		$this->flg_show_page_top 	= $this->params->get('show_pagination_top', '1');
		$this->flg_show_page_bot 	= $this->params->get('show_pagination_bot', '1');	
		$this->flg_show_credit 		= $this->params->get('show_credit','0');
		$this->flg_show_pagination_type = $this->params->get('show_pagination_type','0');
		$this->flg_email_button 	= $this->params->get('flg_email_button', '1');
		$this->flg_show_audio_player = $this->params->get('show_audioPlayer', '0');
		$this->flg_show_second_player = $this->params->get('show_second_player','1');
		$this->str_commentary_width = $this->params->get('commentaryWidth','800');
		$this->str_commentary_height = $this->params->get('commentaryHeight','500');
		$this->str_dictionary_height = $this->params->get('str_dictionary_height','500');
		$this->str_dictionary_width = $this->params->get('str_dictionary_width','800');	
		$this->str_primary_dictionary  = $this->params->get('str_primary_dictionary','');
		$this->flg_show_dictionary = $this->params->get('flg_show_dictionary', 0);
		$this->str_primary_commentary = $this->params->get('primaryCommentary');
		$this->str_tmpl = JRequest::getCmd('tmpl');
		$this->str_curr_dict = JRequest::getCmd('dict');
		$this->str_default_image = $this->params->get('str_default_image', 'media/com_zefaniabible/images/bible_100.jpg');
		
		$this->flg_use_bible_selection 	= $this->params->get('flg_use_bible_selection', '1');
		$this->flg_show_commentary = $this->params->get('show_commentary', '0');
		$this->flg_show_references = $this->params->get('show_references', '0');
		$str_primary_commentary = $this->params->get('primaryCommentary');
		$this->str_commentary = JRequest::getCmd('com',$str_primary_commentary);
		$this->flg_strong_dict = 0;
						
		$obj_Bible_Dropdown = '';
		$str_Chapter_Output = '';
		$obj_Book_Dropdown = '';
		$int_Bibles_loaded = 0;
		$a=1;
		$b=1;
		$str_descr = '';
		$str_alias = '';
		$str_alias2= '';
		$this->arr_commentary_list = $arr_commentary_list;
		$this->arr_dictionary_list = $arr_dictionary_list;

		// make english strings
		$jlang = JFactory::getLanguage();
		$jlang->load('com_zefaniabible', JPATH_COMPONENT, 'en-GB', true);
		for($i = 1; $i <=66; $i++)
		{
			$this->arr_english_book_names[$i] = JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$i);
		}
		$jlang->load('com_zefaniabible', JPATH_COMPONENT, null, true);
						
		if(!$this->str_curr_dict)
		{
			$this->str_curr_dict = $this->str_primary_dictionary;
		}
		
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
			if($str_Bible_Version2 == $str_Bible->alias)
			{
				$this->obj_Bible_Dropdown2 = $this->obj_Bible_Dropdown2.'<option value="'.$str_Bible->alias.'" selected>'.$str_Bible->bible_name.'</option>';
				$str_alias2 = $str_Bible->alias;
			}
			else
			{
				$this->obj_Bible_Dropdown2 = $this->obj_Bible_Dropdown2.'<option value="'.$str_Bible->alias.'" >'.$str_Bible->bible_name.'</option>';
			}			
			$this->int_Bibles_loaded++;
		}
		
		
		for($x = 1; $x <=66; $x++)
		{
			if($int_Bible_Book_ID == $x)
			{
				$this->obj_Book_Dropdown = $this->obj_Book_Dropdown. '<option value="'.$x."-".strtolower(str_replace(" ","-",$this->arr_english_book_names[$x])).'" selected>'.JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$x).'</option>';						
			}
			else
			{
				$this->obj_Book_Dropdown = $this->obj_Book_Dropdown. '<option value="'.$x."-".strtolower(str_replace(" ","-",$this->arr_english_book_names[$x])).'" >'.JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$x).'</option>';				
			}
		}

		$str_match_fuction = "/(?=\S)([HG](\d{1,4}))/iu";
		foreach($arr_Chapter as $arr_verse)
		{
			$arr_verse->verse = preg_replace_callback( $str_match_fuction, array( &$this, 'fnc_Make_Scripture'),  $arr_verse->verse);
			
			if($arr_verse->verse_id == 1)
			{
				$str_descr	= $str_descr.$arr_verse->verse;
			}
			$temp[$a] = '<div class="zef_compare_bibles">'.'<div class="zef_verse_number">'.$arr_verse->verse_id.'</div><div class="zef_verse">'.$arr_verse->verse.'</div></div>';		
			$a++;
		}
		foreach($arr_Chapter2 as $arr_verse2)
		{
			$arr_verse2->verse = preg_replace_callback( $str_match_fuction, array( &$this, 'fnc_Make_Scripture'),  $arr_verse2->verse);
						
			if($arr_verse2->verse_id == 1)
			{
				$str_descr	= $str_descr.$arr_verse2->verse;
			}			
			$temp2[$b] = '<div class="zef_compare_bibles">'.'<div class="zef_verse_number">'.$arr_verse2->verse_id.'</div><div class="zef_verse">'.$arr_verse2->verse.'</div></div>';
			$b++;
								
		}
		
		for($x=1; $x< $a; $x++)
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
			for($y = 1; $y <= 2; $y++)
			{
				if($y %2 )
				{
					$this->str_Chapter_Output  = $this->str_Chapter_Output. '<div class="zef_bible_1">'.$temp[$x].'</div>';
				}
				else
				{
					$this->str_Chapter_Output  = $this->str_Chapter_Output. '<div class="zef_bible_2">'.$temp2[$x].'</div>';
					if($this->flg_show_references)
					{
						foreach($arr_references as $obj_references)
						{
							if($obj_references->verse_id == $x)
							{
								$temp_link = 'a='.$str_Bible_Version.'&b='.$int_Bible_Book_ID.'&c='.$int_Bible_Chapter.'&d='.$x;
								$str_pre_link = '<a title="'. JText::_('COM_ZEFANIA_BIBLE_SCRIPTURE_BIBLE_LINK')." ".'" target="blank" href="index.php?view=references&option=com_zefaniabible&tmpl=component&'.$temp_link.'" class="modal" rel="{handler: \'iframe\', size: {x:'.$this->str_commentary_width.',y:'.$this->str_commentary_height.'}}">';
								$this->str_Chapter_Output  = $this->str_Chapter_Output.'<div class="zef_reference_hash">'.$str_pre_link.JText::_('ZEFANIABIBLE_BIBLE_REFERENCE_LINK').'</a></div>';	
								break;
							}
						}
					}				
					if($this->flg_show_commentary)
					{
						foreach($arr_commentary as $int_verse_commentary)
						{
							if($x == $int_verse_commentary->verse_id)
							{
								$str_commentary_url = JRoute::_("index.php?option=com_zefaniabible&view=commentary&a=".$this->str_commentary."&b=".$int_Bible_Book_ID."&c=".$int_Bible_Chapter."&d=".$x."&tmpl=component");
								$this->str_Chapter_Output  = $this->str_Chapter_Output.'<div class="zef_commentary_hash"><a href="'.$str_commentary_url.'" class="modal" rel="{handler: \'iframe\', size: {x:'.$this->str_commentary_width.',y:'.$this->str_commentary_height.'}}">'.JText::_('ZEFANIABIBLE_BIBLE_COMMENTARY_LINK')."</a></div>";
							}
						}
					}						
				}
				
			}
			$this->str_Chapter_Output  = $this->str_Chapter_Output.'<div style="clear:both"></div></div>';			
		}
		$this->fnc_meta_data($int_Bible_Book_ID, $int_Bible_Chapter,$str_descr,$str_alias,$str_alias2);
	}
	private function fnc_Make_Scripture(&$arr_matches)
	{
		$this->flg_strong_dict = 1;
		$str_verse='';
		if(JRequest::getCmd('strong') == 1)
		{
			$temp = 'a='.$this->str_curr_dict.'&b='.trim(strip_tags($arr_matches[0]));
			$str_verse = ' <a id="zef_strong_link" title="'. JText::_('COM_ZEFANIA_BIBLE_STRONG_LINK').'" target="blank" href="index.php?view=strong&option=com_zefaniabible&tmpl=component&'.$temp.'" class="modal" rel="{handler: \'iframe\', size: {x:'.$this->str_dictionary_width.',y:'.$this->str_dictionary_height.'}}">';		
			$str_verse = $str_verse. trim(strip_tags($arr_matches[0]));			
			$str_verse = $str_verse. '</a> ';
		}
		return $str_verse;
	}
	private function fnc_meta_data($int_Bible_Book_ID, $int_Bible_Chapter,$str_descr,$str_alias,$str_alias2)
	{
		// add breadcrumbs
		$app_site = JFactory::getApplication();
		$pathway = $app_site->getPathway();
		$pathway->addItem(JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$int_Bible_Book_ID)." ".mb_strtolower(JText::_('ZEFANIABIBLE_BIBLE_CHAPTER'),'UTF-8')." ".$int_Bible_Chapter." - ".$str_alias." - ".$str_alias2, JFactory::getURI()->toString());		
		
		$str_descr = trim(mb_substr($str_descr,0,146))." ...";
		$str_title = JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$int_Bible_Book_ID)." ".mb_strtolower(JText::_('ZEFANIABIBLE_BIBLE_CHAPTER'),'UTF-8')." ".$int_Bible_Chapter.' - '.$str_alias.' - '.$str_alias2;
		$this->doc_page->setMetaData( 'description', strip_tags($str_descr));
		$this->doc_page->setMetaData( 'keywords', $str_title.",".$str_alias.",".$str_alias2 );
		$this->doc_page->setTitle($str_title);
					
		// Facebook Open Graph
		$this->doc_page->setMetaData( 'og:title', $str_title);
		$this->doc_page->setMetaData( 'og:url', JFactory::getURI()->toString());		
		$this->doc_page->setMetaData( 'og:type', "article" );	
		$this->doc_page->setMetaData( 'og:image', JURI::root().$this->str_default_image );	
		$this->doc_page->setMetaData( 'og:description', strip_tags($str_descr) );
		$this->doc_page->setMetaData( 'og:site_name', $app_site->getCfg('sitename') );			
	}
	public function fnc_Pagination_Buttons($str_Bible_Version, $int_Bible_Book_ID, $int_Bible_Chapter, $int_max_chapter, $str_Bible_Version2)
	{	
		$urlPrepend = "document.location.href=('";
		$urlPostpend = "')";
		$str_other_url_var = '';
		if(($this->flg_show_commentary)and(count($this->arr_commentary_list) > 1))
		{
				$str_other_url_var = $str_other_url_var."&com=".$this->str_commentary;
		}
		if($this->str_tmpl == "component")
		{
			$str_other_url_var = $str_other_url_var. "&tmpl=component";
		}
		if(($this->flg_show_dictionary)and(count($this->arr_dictionary_list) > 1))
		{
			$str_other_url_var = $str_other_url_var. "&dict=".$this->str_curr_dict;
		}
		if(($this->flg_strong_dict)and(JRequest::getCmd('strong') ==1))
		{
			$str_other_url_var = $str_other_url_var."&strong=".JRequest::getCmd('strong');
		}		
		
		if($int_Bible_Book_ID > 1)
		{
			$url[3] = "index.php?option=com_zefaniabible&a=".$str_Bible_Version."&b=".$str_Bible_Version2."&view=".JRequest::getCmd('view')."&c=".
			($int_Bible_Book_ID-1)."-".strtolower(str_replace(" ","-",$this->arr_english_book_names[($int_Bible_Book_ID-1)]))."&d=1-chapter".$str_other_url_var;
			
			$url[3] = JRoute::_($url[3]);
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
			$url[1] = "index.php?option=com_zefaniabible&a=".$str_Bible_Version."&b=".$str_Bible_Version2."&view=".JRequest::getCmd('view')."&c=".
			$int_Bible_Book_ID."-".strtolower(str_replace(" ","-",$this->arr_english_book_names[$int_Bible_Book_ID]))."&d=".($int_Bible_Chapter-1).
			"-chapter".$str_other_url_var;	
			
			$url[1] = JRoute::_($url[1]);
				
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
			$url[0] = "index.php?option=com_zefaniabible&a=".$str_Bible_Version."&b=".$str_Bible_Version2."&view=".
			JRequest::getCmd('view')."&c=".$int_Bible_Book_ID."-".strtolower(str_replace(" ","-",$this->arr_english_book_names[$int_Bible_Book_ID]))."&d=".
			($int_Bible_Chapter+1)."-chapter".$str_other_url_var;	
			
			$url[0] = JRoute::_($url[0]);	
						
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
			$url[2] = "index.php?option=com_zefaniabible&a=".$str_Bible_Version."&b=".$str_Bible_Version2."&view=".JRequest::getCmd('view')."&c=".
			($int_Bible_Book_ID+1)."-".strtolower(str_replace(" ","-",$this->arr_english_book_names[($int_Bible_Book_ID+1)]))."&d=1-chapter".$str_other_url_var;
			$url[2] = JRoute::_($url[2]);
			
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
	public function fnc_dictionary_dropdown($arr_dictionary_list)
	{
		$obj_dropdown = '';
		foreach($arr_dictionary_list as $obj_dictionary)
		{
			if(JRequest::getCmd('dict') == $obj_dictionary->alias)
			{
				$obj_dropdown = $obj_dropdown.'<option value="'.$obj_dictionary->alias.'" selected>'.$obj_dictionary->name.'</option>';
			}
			else
			{
				$obj_dropdown = $obj_dropdown.'<option value="'.$obj_dictionary->alias.'">'.$obj_dictionary->name.'</option>';
			}
		}
		return $obj_dropdown;	
	}	
}
?>

<form action="<?php echo JFactory::getURI()->toString(); ?>" method="post" id="adminForm" name="adminForm">
	<div id="zef_Bible_Main">
    	<div class="zef_legend">
        	<?php if($cls_bibleBook->flg_email_button){?>
            <div class="zef_email_button"><a title="<?php echo JText::_('ZEFANIABIBLE_EMAIL_BUTTON_TITLE'); ?>" target="blank" href="index.php?view=subscribe&option=com_zefaniabible&tmpl=component" class="modal" rel="{handler: 'iframe', size: {x:500,y:400}}" ><img class="zef_email_img" src="<?php echo JURI::root()."media/com_zefaniabible/images/e_mail.png"; ?>" width="24" height="24" alt="<?php echo JText::_('ZEFANIABIBLE_RSS_BUTTON_TITLE'); ?>" /></a></div>
            <?php } ?>
            <div class="zef_bible_Header_Label"><h1 class="zef_bible_Header_Label_h1"><?php echo JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$this->int_Bible_Book_ID)." ".mb_strtolower(JText::_('ZEFANIABIBLE_BIBLE_CHAPTER'),'UTF-8')." ".$this->int_Bible_Chapter; ?></h1></div>
            <?php if(($cls_bibleBook->flg_use_bible_selection)and(count($this->arr_Bibles) > 1)){?>            
                <div class="zef_bible_label"><?php echo JText::_('ZEFANIABIBLE_BIBLE_VERSION_FIRST');?></div>
                <div class="zef_bible">
                    <select name="a" id="bible" class="inputbox" onchange="this.form.submit()">
                        <?php echo $cls_bibleBook->obj_Bible_Dropdown; ?>
                    </select>
                </div>
				<div style="clear:both;"></div>                
               <div class="zef_bible_label"><?php echo JText::_('ZEFANIABIBLE_BIBLE_VERSION_SECOND');?></div>
                <div class="zef_bible">
                    <select name="b" id="bible" class="inputbox" onchange="this.form.submit()">
                        <?php echo $cls_bibleBook->obj_Bible_Dropdown2; ?>
                    </select>
                </div>                
            <?php } else {
				echo '<input type="hidden" name="a" value="'.$this->str_Bible_Version.'" />';
				echo '<input type="hidden" name="b" value="'.$this->str_Bible_Version2.'" />';
             }?> 			
            <div class="zef_book_label"><?php echo JText::_('ZEFANIABIBLE_BIBLE_BOOK');?></div>
            <div class="zef_book">
                <select name="c" id="book" class="inputbox" onchange="this.form.submit()">
					<?php 
						echo $cls_bibleBook->obj_Book_Dropdown;
					?>
                </select>
            </div>

            <div class="zef_chapter_label"><?php echo JText::_('ZEFANIABIBLE_BIBLE_CHAPTER');?></div>
            <div class="zef_Chapter">
                <select name="d" id="chapter" class="inputbox" onchange="this.form.submit()">
					<?php 
						for( $x = 1; $x <= $this->int_max_chapter; $x++)
						{
							if($x == $this->int_Bible_Chapter)
							{
								echo '<option value="'.$x.'-chapter" selected="selected">'.$x.'</option>';
							}
							else
							{
								echo '<option value="'.$x.'-chapter">'.$x.'</option>';
							}
						}
					?>               
                </select>
            </div>
             <div style="clear:both;"></div>
			<?php if($cls_bibleBook->flg_show_commentary){ 
					if(count($this->arr_commentary_list)> 1){
			?>
                    <div>
                        <div class="zef_commentary_label"><?php echo JText::_('COM_ZEFANIABIBLE_COMMENTARY_LABEL');?></div>
                        <div class="zef_commentary">
                            <select name="com" id="commentary" class="inputbox" onchange="this.form.submit()">
                                <?php echo $this->obj_commentary_dropdown;?>
                             </select>
                        </div>
                    </div>
					<?php }else{?>
                       	<!--<input type="hidden" name="com" value="<?php echo $cls_bibleBook->str_primary_commentary;?>" />-->
            <?php }} ?>
			<?php if($cls_bibleBook->flg_show_dictionary){
						if((count($this->arr_dictionary_list) > 1)and($cls_bibleBook->flg_strong_dict)){?>
                            <div id="zef_dictionary_div">
                                <div class="zef_dictionary_label"><?php echo JText::_('COM_ZEFANIABIBLE_DICTIONARY_LABEL');?></div>
                                <div class="zef_dictionary">
                                    <select name="dict" id="dictionary" class="inputbox" onchange="this.form.submit()">
                                        <?php echo $cls_bibleBook->fnc_dictionary_dropdown($this->arr_dictionary_list);?>
                                     </select>
                                </div>
                            </div>
					<?php }?>
                    	<?php if($cls_bibleBook->flg_strong_dict){?>
							<div class="zef_dictionary_strong_box">
                            	<div class="zef_dictionary_strong_label"><?php echo JText::_('COM_ZEFANIABIBLE_HIDE_STRONG');?></div>
								<div class="zef_dictionary_strong_input">
	                                <input type='hidden' value='0' name='strong'>
                                	<input type='checkbox' name='strong' value="1" id='zef_hide_strong' <?php if(JRequest::getCmd('strong') == 1){ echo 'checked="checked"';}?> onchange="this.form.submit()" />
								</div>
							</div>
	                    <?php } ?>      
                   <?php } ?>
                 
             <div style="clear:both;"></div>
            <div class="zef_top_pagination">
         		<?php if($cls_bibleBook->flg_show_page_top){ $cls_bibleBook->fnc_Pagination_Buttons($this->str_Bible_Version,$this->int_Bible_Book_ID, $this->int_Bible_Chapter, $this->int_max_chapter,$this->str_Bible_Version2);} ?>
            </div>              
        </div>   
             <div class="zef_player">
			<?php if(($cls_bibleBook->flg_show_audio_player)and($this->obj_player_one)){ ?>              
             	<div class="zef_player-1"> 
                	<?php echo $this->obj_player_one; 
							echo '<div style="clear:both;"></div>';
				            echo  '<a href="#" onclick="return popitup(\''.JURI::root().'index.php?option=com_zefaniabible&a='.$this->str_Bible_Version.'&view=player&tmpl=component&b='.$this->int_Bible_Book_ID.'\')" target="_blank" >'.JText::_('ZEFANIABIBLE_PLAYER_WHOLE_BOOK')."</a>";
					?>
                </div>
            <?php }?>                   
                <?php if(($cls_bibleBook->flg_show_audio_player)and($cls_bibleBook->flg_show_second_player)and($this->obj_player_two)){?>
                <div class="zef_player-2"> 
               		<?php echo $this->obj_player_two; 
							echo '<div style="clear:both;"></div>';
				            echo  '<a href="#" onclick="return popitup(\''.JURI::root().'index.php?option=com_zefaniabible&a='.$this->str_Bible_Version2.'&view=player&tmpl=component&b='.$this->int_Bible_Book_ID.'\')" target="_blank" >'.JText::_('ZEFANIABIBLE_PLAYER_WHOLE_BOOK')."</a>";					
					?>
                </div>
                <?php }?>
            </div>

        <div style="clear:both;"></div>
        <div class="zef_bible_Chapter"><article><?php echo $cls_bibleBook->str_Chapter_Output; ?></article></div>     
        <div class="zef_footer">
            <div class="zef_bot_pagination">
            	<?php if($cls_bibleBook->flg_show_page_bot){ $cls_bibleBook->fnc_Pagination_Buttons($this->str_Bible_Version,$this->int_Bible_Book_ID, $this->int_Bible_Chapter, $this->int_max_chapter, $this->str_Bible_Version2);} ?>        
            	<div style="clear:both;"></div>
	            <?php 
				if(($cls_bibleBook->flg_show_credit)or(JRequest::getInt('Itemid') == 0 ))
				{
					require_once(JPATH_COMPONENT_SITE.'/helpers/credits.php');
					$mdl_credits = new ZefaniabibleCredits;
					$obj_player_one = $mdl_credits->fnc_credits();
				} ?>
                    
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
<?php if(($cls_bibleBook->str_commentary_width <= 1)or($cls_bibleBook->str_commentary_height <= 1)){?>
<script>
		<?php if($cls_bibleBook->str_commentary_width <= 1){?>
        	var ScreenX = (screen.width)? Math.round(getWidth()*<?php echo $cls_bibleBook->str_commentary_width;?>):800;
		<?php }else{?>
		 	var ScreenX = <?php echo $cls_bibleBook->str_commentary_width;?>;
		<?php }?>
		<?php if($cls_bibleBook->str_commentary_height <= 1){?>
        	var ScreenY = (screen.height)? Math.round(getHeight()*<?php echo $cls_bibleBook->str_commentary_height;?>):600;
		<?php }else{?>
			var ScreenY = <?php echo $cls_bibleBook->str_commentary_height;?>;
		<?php }?>  
   var Alinks = $$('a.modal');
   function ModalRelation() {
      this.handler = "'iframe'";
      this.x = 800;
      this.y = 600;
   }
   ModalRelation.prototype.toString = function ModalRelationtoString() {
      var ret = "{handler:"+this.handler+",size:{x:"+this.x+",y:"+this.y+"}}";
      return ret;
   }
   ModalRelation.prototype.setSize = function ModalSize(x,y) {
      this.x = x;
      this.y = y;
   }
   var ModalRel = new ModalRelation();
   ModalRel.setSize(ScreenX,ScreenY);
   Alinks.each(function(obj,idx){
      obj.setProperty("rel",ModalRel.toString());
   });
</script>
<?php }?>  