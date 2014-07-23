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
defined('_JEXEC') or die('Restricted access');
class ZefaniabibleCommonHelper
{
	public function fnc_load_languages()
	{
		// make english strings
		$jlang = JFactory::getLanguage();
		$jlang->load('com_zefaniabible', JPATH_COMPONENT, 'en-GB', true);
		for($i = 1; $i <=66; $i++)
		{
			$arr_english_book_names[$i] = JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$i);
		}
		$jlang->load('com_zefaniabible', JPATH_COMPONENT, null, true);
		return $arr_english_book_names;
	}
	public function fnc_redirect_last_chapter($item)
	{		
		// redirect to last chapter
		if($item->int_Bible_Chapter > $item->int_max_chapter)
		{
			$str_redirect_url = "index.php?option=com_zefaniabible&view=".$item->str_view."&bible=".$item->str_Bible_Version."&book=".$item->int_Bible_Book_ID.'-'.strtolower(str_replace(" ","-",$item->arr_english_book_names[$item->int_Bible_Book_ID]))."&chapter=".$item->int_max_chapter.'-chapter';
			if(($item->flg_show_commentary)and(count($item->arr_commentary_list) > 1))
			{
				$str_redirect_url .= "&com=".$item->str_primary_commentary;
			}
			if($item->str_tmpl == "component")
			{
				$str_redirect_url .= "&tmpl=component";
			}
			if(($item->flg_show_dictionary)and(count($item->arr_dictionary_list) > 1))
			{
				$str_redirect_url .= "&dict=".$item->str_primary_dictionary;
			}
			if($item->flg_use_strong == 1)
			{
				$str_redirect_url .= "&strong=".$item->flg_use_strong;
			}			
			$str_redirect_url = JRoute::_($str_redirect_url);
			header('HTTP/1.1 301 Moved Permanently');
			header('Location: '.$str_redirect_url); 		
		}				
	}
	public function fnc_meta_data($item)
	{
		// add breadcrumbs
		$app_site = JFactory::getApplication();
		$pathway = $app_site->getPathway();		
		$doc_page = JFactory::getDocument();	
		//$attribs_atom = '';
		$href_atom = '';
		$str_descr = '';
		switch ($item->str_view)
		{
			case 'standard':
				$str_title = JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$item->int_Bible_Book_ID)." ".mb_strtolower(JText::_('ZEFANIABIBLE_BIBLE_CHAPTER'),'UTF-8')." ".$item->int_Bible_Chapter.' - '.$item->str_Bible_Version;			
				$doc_page->setMetaData( 'keywords', $str_title.",".$item->str_Bible_Version.", ".$item->str_bible_name );				
				$pathway->addItem(JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$item->int_Bible_Book_ID)." ".mb_strtolower(JText::_('ZEFANIABIBLE_BIBLE_CHAPTER'),'UTF-8')." ".$item->int_Bible_Chapter." - ".$item->str_Bible_Version, JFactory::getURI()->toString());
				$href_rss = 'index.php?option=com_zefaniabible&view=biblerss&format=raw&bible='.$item->str_Bible_Version.'&book='.$item->int_Bible_Book_ID.'&chapter='.$item->int_Bible_Chapter; 				
				$href_atom = 'index.php?option=com_zefaniabible&view=biblerss&format=raw&bible='.$item->str_Bible_Version.'&book='.$item->int_Bible_Book_ID.'&chapter='.$item->int_Bible_Chapter.'&type=atom'; 
				break;			

			case 'compare':
				$str_title = JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$item->int_Bible_Book_ID)." ".mb_strtolower(JText::_('ZEFANIABIBLE_BIBLE_CHAPTER'),'UTF-8')." ".$item->int_Bible_Chapter.' - '.$item->str_Main_Bible_Version.', '. $item->str_Second_Bible_Version;				
				$doc_page->setMetaData( 'keywords', $str_title.",".$item->str_Bible_Version.", ".$item->str_bible_name_1 .", ".$item->str_bible_name_2);				
				$pathway->addItem(JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$item->int_Bible_Book_ID)." ".mb_strtolower(JText::_('ZEFANIABIBLE_BIBLE_CHAPTER'),'UTF-8')." ".$item->int_Bible_Chapter." - ".$item->str_Bible_Version, JFactory::getURI()->toString());
				$href_rss = 'index.php?option=com_zefaniabible&view=biblerss&format=raw&bible='.$item->str_Bible_Version.'&book='.$item->int_Bible_Book_ID.'&chapter='.$item->int_Bible_Chapter; 				
				$href_atom = 'index.php?option=com_zefaniabible&view=biblerss&format=raw&bible='.$item->str_Bible_Version.'&book='.$item->int_Bible_Book_ID.'&chapter='.$item->int_Bible_Chapter.'&type=atom'; 
				break;
			case 'reading':	
				$pathway->addItem(($item->str_reading_plan_name." - ". mb_strtoupper($item->str_Bible_Version)." - ".JText::_('ZEFANIABIBLE_READING_PLAN_DAY')." ".$item->int_day_number), JFactory::getURI()->toString());					
				$href_rss = 'index.php?option=com_zefaniabible&view=readingrss&format=raw&plan='.$item->str_reading_plan.'&bible='.$item->str_Bible_Version.'&day='.$item->int_day_number; 
				$str_title = $item->str_reading_plan_name." | ". mb_strtoupper($item->str_Bible_Version)." | ".JText::_('ZEFANIABIBLE_READING_PLAN_DAY')." ".$item->int_day_number;						
				break;
			default:
			 	break;
		}
				
		//RSS RSS 2.0 Feed

		$attribs = array('type' => 'application/rss+xml', 'title' => 'RSS 2.0'); 
		$doc_page->addHeadLink( $href_rss, 'alternate', 'rel', $attribs );
		//Atom Feed

		$attribs = array('type' => 'application/atom+xml', 'title' => 'Atom 1.0'); 
		$doc_page->addHeadLink( $href_atom, 'alternate', 'rel', $attribs );		
				
		$str_descr = trim(mb_substr($item->str_description,0,146))." ..."; 

		$doc_page->setMetaData( 'description', strip_tags($str_descr));
		$doc_page->setTitle($str_title);
					
		// Facebook Open Graph
		$doc_page->setMetaData( 'og:title', $str_title);	
		$doc_page->setMetaData( 'og:url', JFactory::getURI()->toString());		
		$doc_page->setMetaData( 'og:type', "article" );	
		$doc_page->setMetaData( 'og:image', JURI::root().$item->str_default_image );	
		$doc_page->setMetaData( 'og:description', strip_tags($str_descr) );
		$doc_page->setMetaData( 'og:site_name', $app_site->getCfg('sitename') );	
	}
	public function fnc_Pagination_Buttons($item)
	{	
		$urlPrepend = "document.location.href=('";
		$urlPostpend = "')";
		$str_other_url_var = '';
		
/*		if(($item->flg_show_commentary)and(count($this->arr_commentary_list) > 1))
		{
				$str_other_url_var .= "&com=".$item->str_commentary;
		}*/
		if($item->str_tmpl == "component")
		{
			$str_other_url_var .= "&tmpl=component";
		}
	
/*		if(($item->flg_show_dictionary)and(count($this->arr_dictionary_list) > 1))
		{
			$str_other_url_var .= "&dict=".$this->str_curr_dict;
		}*/
		if($item->flg_use_strong == 1)
		{
			$str_other_url_var .= "&strong=".$item->flg_use_strong;
		}
		if($item->int_Bible_Book_ID > 1)
		{
			$url[3] = "index.php?option=com_zefaniabible&bible=".$item->str_Bible_Version."&view=".$item->str_view."&book=".
			($item->int_Bible_Book_ID-1)."-".strtolower(str_replace(" ","-",$item->arr_english_book_names[($item->int_Bible_Book_ID-1)]))."&chapter=1-chapter".$str_other_url_var;
			if($item->str_view == 'compare')
			{
				$url[3] .= '&bible2='.$item->str_Second_Bible_Version;
			}
			$url[3] = JRoute::_($url[3]);
			if($item->flg_show_pagination_type == 0)
			{
				echo '<input title="'.JText::_('ZEFANIABIBLE_BIBLE_LAST_BOOK').'" type="button" id="zef_Buttons" class="zef_lastBook" name="lastBook" onclick="'.$urlPrepend.$url[3].$urlPostpend.'"  value="'. JText::_("ZEFANIABIBLE_BIBLE_BOOK_NAME_".($item->int_Bible_Book_ID-1)).' 1"'.' />';
			}
			else
			{
				echo "<a title='".JText::_('ZEFANIABIBLE_BIBLE_LAST_BOOK')."' id='zef_links' href='".$url[3]."'>".JText::_("ZEFANIABIBLE_BIBLE_BOOK_NAME_".($item->int_Bible_Book_ID-1)).' 1'."</a> ";
			}
		}
		if($item->int_Bible_Chapter > 1)
		{
			$url[1] = "index.php?option=com_zefaniabible&bible=".$item->str_Bible_Version."&view=".$item->str_view."&book=".
			$item->int_Bible_Book_ID."-".strtolower(str_replace(" ","-",$item->arr_english_book_names[$item->int_Bible_Book_ID]))."&chapter=".($item->int_Bible_Chapter-1).
			"-chapter".$str_other_url_var;	
			if($item->str_view == 'compare')
			{
				$url[1] .= '&bible2='.$item->str_Second_Bible_Version;
			}
			$url[1] = JRoute::_($url[1]);
			if($item->flg_show_pagination_type == 0)
			{
				echo '<input title="'.JText::_('ZEFANIABIBLE_BIBLE_LAST_CHAPTER').'" type="button" id="zef_Buttons" class="zef_lastChapter" name="lastChapter" onclick="'.$urlPrepend.$url[1].$urlPostpend.'"  value="'. JText::_("ZEFANIABIBLE_BIBLE_BOOK_NAME_".$item->int_Bible_Book_ID)." ".($item->int_Bible_Chapter-1).'" />';
			}
			else
			{
				echo "<a title='".JText::_('ZEFANIABIBLE_BIBLE_LAST_CHAPTER')."' id='zef_links' href='".$url[1]."'>".JText::_("ZEFANIABIBLE_BIBLE_BOOK_NAME_".$item->int_Bible_Book_ID).' '.($item->int_Bible_Chapter-1)."</a> ";
			}
		}
		if($item->int_Bible_Chapter < $item->int_max_chapter)
		{
			$url[0] = "index.php?option=com_zefaniabible&bible=".$item->str_Bible_Version."&view=".
			$item->str_view."&book=".$item->int_Bible_Book_ID."-".strtolower(str_replace(" ","-",$item->arr_english_book_names[$item->int_Bible_Book_ID]))."&chapter=".
			($item->int_Bible_Chapter+1)."-chapter".$str_other_url_var;	
			if($item->str_view == 'compare')
			{
				$url[0] .= '&bible2='.$item->str_Second_Bible_Version;
			}
			$url[0] = JRoute::_($url[0]);
			
			if($item->flg_show_pagination_type == 0)
			{			
				echo '<input title="'.JText::_('ZEFANIABIBLE_BIBLE_NEXT_CHAPTER').'" type="button" id="zef_Buttons" class="zef_NextChapter" name="nextChapter" onclick="'.$urlPrepend.$url[0].$urlPostpend.'"  value="'. JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$item->int_Bible_Book_ID)." ".($item->int_Bible_Chapter+1).'" />';
			}
			else
			{
				echo "<a title='".JText::_('ZEFANIABIBLE_BIBLE_NEXT_CHAPTER')."' id='zef_links' href='".$url[0]."'>".JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$item->int_Bible_Book_ID).' '.($item->int_Bible_Chapter+1)."</a> ";
			}
		}
		if($item->int_Bible_Book_ID < 66)
		{
			$url[2] = "index.php?option=com_zefaniabible&bible=".$item->str_Bible_Version."&view=".$item->str_view."&book=".
			($item->int_Bible_Book_ID+1)."-".strtolower(str_replace(" ","-",$item->arr_english_book_names[($item->int_Bible_Book_ID+1)]))."&chapter=1-chapter".$str_other_url_var;
			if($item->str_view == 'compare')
			{
				$url[2] .= '&bible2='.$item->str_Second_Bible_Version;
			}
			$url[2] = JRoute::_($url[2]);
			
			if($item->flg_show_pagination_type == 0)
			{			
				echo '<input title="'.JText::_('ZEFANIABIBLE_BIBLE_NEXT_BOOK').'" type="button" id="zef_Buttons" class="zef_NextBook" name="nextBook" onclick="'.$urlPrepend.$url[2].$urlPostpend.'"  value="'. JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.($item->int_Bible_Book_ID+1)).' 1"'.' />';
			}
			else
			{
				echo "<a title='".JText::_('ZEFANIABIBLE_BIBLE_NEXT_BOOK')."' id='zef_links' href='".$url[2]."'>". JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.($item->int_Bible_Book_ID+1)).' 1'."</a>";
			} 
		}
	}
	public function fnc_Pagination_Buttons_day($item)
	{
		$urlPrepend = "document.location.href=('";
		$urlPostpend = "')";	
		$str_other_url_var = '';	
		if(($item->flg_show_commentary)and(count($item->arr_commentary_list) > 1))
		{
				$str_other_url_var .= "&com=".$item->str_commentary;
		}
		if($item->str_tmpl == "component")
		{
			$str_other_url_var .=  "&tmpl=component";
		}
		if(($item->flg_show_dictionary)and(count($item->arr_dictionary_list) > 1))
		{
			$str_other_url_var .=  "&dict=".$item->str_curr_dict;
		}
		if($item->flg_use_strong ==1)
		{
			$str_other_url_var .= "&strong=".$item->flg_use_strong;
		}		
		// fix days yesterday's day when less than 1
		if($item->int_day_number <= 1)
		{
			$str_yesterday = $item->int_max_days;
		}
		else
		{
			$str_yesterday = ($item->int_day_number-1);
		}
		
		// make yesterday's link/button
		$url[2] = "index.php?option=com_zefaniabible&plan=".$item->str_reading_plan."&bible=".$item->str_Bible_Version."&view=".$item->str_view."&day=".$str_yesterday.$str_other_url_var;
	
		$url[2] = JRoute::_($url[2]);			
		if($item->flg_show_pagination_type == 0)
		{
			echo '<input title="'.JText::_('ZEFANIABIBLE_BIBLE_LAST_DAY_READING').'" type="button" id="zef_Buttons" class="zef_last_day" name="lastday" onclick="'.$urlPrepend.$url[2].$urlPostpend.'"  value="'.JText::_('ZEFANIABIBLE_READING_PLAN_DAY').' '.$str_yesterday.'" />';
		}
		else
		{
			echo "<a title='".JText::_('ZEFANIABIBLE_BIBLE_LAST_DAY_READING')."' id='zef_links' href='".$url[2]."'>".JText::_('ZEFANIABIBLE_READING_PLAN_DAY')." ".$str_yesterday."</a> ";
		}
		
		// make today's text or disabled button
		if($item->flg_show_pagination_type == 0)
		{
			echo '<input title="'.JText::_('').'" type="button" id="zef_Buttons" disabled="disabled" class="zef_today" name="today" onclick="'.$urlPrepend.$url[2].$urlPostpend.'"  value="'.JText::_('ZEFANIABIBLE_READING_PLAN_DAY').' '.($item->int_day_number).'" />';		
		}
		else
		{
			echo JText::_('ZEFANIABIBLE_READING_PLAN_DAY')." ".($item->int_day_number);			
		}
		
		// fix tommorow when greater than max days in plan
		if($item->int_day_number >= $item->int_max_days)
		{
			$int_tommorow = 1;
		}
		else
		{
			$int_tommorow = ($item->int_day_number+1);
		}
		
		//make tomorow's link/button
		$url[3] = "index.php?option=com_zefaniabible&plan=".$item->str_reading_plan."&bible=".$item->str_Bible_Version."&view=".$item->str_view."&day=".$int_tommorow.$str_other_url_var;	
		$url[3] = JRoute::_($url[3]);	
		
		if($item->flg_show_pagination_type == 0)
		{
			echo '<input title="'.JText::_('ZEFANIABIBLE_BIBLE_NEXT_DAY_READING').'" type="button" id="zef_Buttons" class="zef_next_day" name="nextday" onclick="'.$urlPrepend.$url[3].$urlPostpend.'"  value="'.JText::_('ZEFANIABIBLE_READING_PLAN_DAY').' '.$int_tommorow.'" />';
		}
		else
		{
			echo "<a title='".JText::_('ZEFANIABIBLE_BIBLE_NEXT_DAY_READING')."' id='zef_links' href='".$url[3]."'>".JText::_('ZEFANIABIBLE_READING_PLAN_DAY')." ".$int_tommorow."</a> ";
		}
	}	
	public function fnc_find_bible_name($arr_Bibles, $str_Bible_Version)
	{
		$str_bible_name = '';
		foreach($arr_Bibles as $obj_bibles)
		{
			if($str_Bible_Version == $obj_bibles->alias)
			{
				$str_bible_name = $obj_bibles->bible_name;
			}
		}
		return $str_bible_name;
	}
	public function fnc_find_reading_name($arr_reading, $str_reading_plan)
	{
		$str_reading_name = '';
		foreach($arr_reading as $obj_plan)
		{
			if($str_reading_plan == $obj_plan->alias)
			{
				$str_reading_name = $obj_plan->name;
			}
		}
		return $str_reading_name;
	}		
	public function fnc_dictionary_dropdown($item)
	{
		$obj_dropdown = '';
		foreach($arr_dictionary_list as $obj_dictionary)
		{
			if($item->str_curr_dict == $obj_dictionary->alias)
			{
				$obj_dropdown = $obj_dropdown.'<option value="'.$obj_dictionary->alias.'" selected>'.$obj_dictionary->name.'</option>'.PHP_EOL;
			}
			else
			{
				$obj_dropdown = $obj_dropdown.'<option value="'.$obj_dictionary->alias.'">'.$obj_dictionary->name.'</option>'.PHP_EOL;
			}
		}
		return $obj_dropdown;	
	}
	public function fnc_bible_book_dropdown($item)
	{
		$obj_Book_Dropdown = '';
		for($x = 1; $x <=66; $x++)
		{
			if($item->int_Bible_Book_ID == $x)
			{
				$obj_Book_Dropdown .= '<option value="'.$x."-".strtolower(str_replace(" ","-",$item->arr_english_book_names[$x])).'" selected>'.JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$x).'</option>'.PHP_EOL;						
			}
			else
			{
				$obj_Book_Dropdown .= '<option value="'.$x."-".strtolower(str_replace(" ","-",$item->arr_english_book_names[$x])).'" >'.JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$x).'</option>'.PHP_EOL;				
			}
		}
		return $obj_Book_Dropdown;
	}
	public function fnc_bible_chapter_dropdown($item)
	{
		$obj_Chap_Dropdown = '';
		for( $x = 1; $x <= $item->int_max_chapter; $x++)
		{
			if($x == $item->int_Bible_Chapter)
			{
				$obj_Chap_Dropdown .= '<option value="'.$x.'-chapter" selected="selected">'.$x.'</option>'.PHP_EOL;
			}
			else
			{
				$obj_Chap_Dropdown .= '<option value="'.$x.'-chapter">'.$x.'</option>'.PHP_EOL;
			}
		}
		return $obj_Chap_Dropdown;
	}
	public function fnc_bible_name_dropdown($arr_Bibles,$str_Bible_Version)
	{
		$obj_Bible_Dropdown = '';
		foreach($arr_Bibles as $obj_Bible)
		{
			// Error blank alias found
			if($obj_Bible->alias == '')
			{
				JError::raiseWarning('',str_replace('%s','<b>'.$obj_Bible->bible_name.'</b>',JText::_('ZEFANIABIBLE_ERROR_BLANK_ALIAS_BIBLE')));
			}	
			if($str_Bible_Version == $obj_Bible->alias)
			{
				$obj_Bible_Dropdown .= '<option value="'.$obj_Bible->alias.'" selected>'.$obj_Bible->bible_name.'</option>'.PHP_EOL;
			}
			else
			{
				$obj_Bible_Dropdown .= '<option value="'.$obj_Bible->alias.'" >'.$obj_Bible->bible_name.'</option>'.PHP_EOL;
			}
		}	
		return $obj_Bible_Dropdown;	
	}
	public function fnc_commentary_drop_down($item)
	{
		$obj_commentary_dropdown = '';
		foreach($item->arr_commentary_list as $obj_comm_list)
		{
			if($obj_comm_list->alias == "")
			{
				JError::raiseWarning('',str_replace('%s','<b>'.$obj_comm_list->title.'</b>',JText::_('ZEFANIABIBLE_ERROR_BLANK_ALIAS_COMMENTARY')));
			}
			if($item->str_commentary == $obj_comm_list->alias)
			{
				$obj_commentary_dropdown .= '<option value="'.$obj_comm_list->alias.'" selected>'.$obj_comm_list->title.'</option>'.PHP_EOL;
			}
			else
			{
				$obj_commentary_dropdown .= '<option value="'.$obj_comm_list->alias.'">'.$obj_comm_list->title.'</option>'.PHP_EOL;
			}
		}
		return $obj_commentary_dropdown;
	}
	public function fnc_reading_plan_drop_down($item)
	{
		$str_dropdown = '';
		foreach($item->arr_reading_plan_list as $readingplan)
		{
			if($item->str_reading_plan == $readingplan->alias)
			{
				$str_dropdown .= '<option value="'.$readingplan->alias.'" selected>'.$readingplan->name.'</option>';
			}
			else
			{
				$str_dropdown .= '<option value="'.$readingplan->alias.'" >'.$readingplan->name.'</option>';
			}
		}
		return $str_dropdown;		
	}
	public function fnc_output_single_chapter($item)
	{
		$x = 0;
		$str_Chapter_Output = '';
		foreach ($item->arr_Chapter as $arr_verse)
		{
			if($item->flg_use_strong == 1)
			{
				$str_match_fuction = "/(?=\S)([HG](\d{1,4}))/iu";
				$arr_verse->verse = preg_replace_callback( $str_match_fuction, array( &$this, 'fnc_Make_Scripture'),  $arr_verse->verse);
			}		
			if ($x % 2)	
			{
				$str_Chapter_Output .= '<div class="odd">';
			}
			else
			{
				$str_Chapter_Output  .= '<div class="even">'; 
			}
			
			$str_Chapter_Output  .= "<div class='zef_verse_number'>".$arr_verse->verse_id."</div><div class='zef_verse'>".$arr_verse->verse."</div>";
			
			if($item->flg_show_references)
			{
				foreach($item->arr_references as $obj_references)
				{
					if($obj_references->verse_id == $arr_verse->verse_id)
					{
						$temp = 'bible='.$item->str_Bible_Version.'&book='.$item->int_Bible_Book_ID.'&chapter='.$item->int_Bible_Chapter.'&verse='.$arr_verse->verse_id;
						$str_pre_link = '<a title="'. JText::_('COM_ZEFANIA_BIBLE_SCRIPTURE_BIBLE_LINK')." ".'" target="blank" href="index.php?view=references&option=com_zefaniabible&tmpl=component&'.$temp.'" class="modal" rel="{handler: \'iframe\', size: {x:'.$item->str_commentary_width.',y:'.$item->str_commentary_height.'}}">';
						$str_Chapter_Output  .= '<div class="zef_reference_hash">'.$str_pre_link.JText::_('ZEFANIABIBLE_BIBLE_REFERENCE_LINK').'</a></div>';	
						break;
					}
				}
			}		
			
			if($item->flg_show_commentary)
			{
				foreach($item->arr_commentary as $int_verse_commentary)
				{
					if($arr_verse->verse_id == $int_verse_commentary->verse_id)
					{
						$str_commentary_url = JRoute::_("index.php?option=com_zefaniabible&view=commentary&com=".$item->str_commentary."&book=".$item->int_Bible_Book_ID."&chapter=".$item->int_Bible_Chapter."&verse=".$arr_verse->verse_id."&tmpl=component");
						$str_Chapter_Output  .= '<div class="zef_commentary_hash"><a href="'.$str_commentary_url.'" class="modal" rel="{handler: \'iframe\', size: {x:'.$item->str_commentary_width.',y:'.$item->str_commentary_height.'}}">'.JText::_('ZEFANIABIBLE_BIBLE_COMMENTARY_LINK')."</a></div>";
					}
				}
			}			
			$str_Chapter_Output  .= '<div style="clear:both"></div></div>';
			$x++;
		}			
		if( $str_Chapter_Output == "")
		{
			JError::raiseWarning('',JText::_('ZEFANIABIBLE_ERROR_CHAPTER_NOT_FOUND'));
		}
		return $str_Chapter_Output;		
	}
	public function fnc_output_dual_chapter($item)
	{
		$str_match_fuction = "/(?=\S)([HG](\d{1,4}))/iu";
		$a = 1;
		$b = 1;
		$str_Chapter_Output = '';
		$temp2 = '';
		foreach($item->arr_Chapter_1 as $arr_verse)
		{
			$arr_verse->verse = preg_replace_callback( $str_match_fuction, array( &$this, 'fnc_Make_Scripture'),  $arr_verse->verse);	
			$temp[$a] = '<div class="zef_compare_bibles">'.'<div class="zef_verse_number">'.$arr_verse->verse_id.'</div><div class="zef_verse">'.$arr_verse->verse.'</div></div>';		
			$a++;
		}
		foreach($item->arr_Chapter_2 as $arr_verse2)
		{
			$arr_verse2->verse = preg_replace_callback( $str_match_fuction, array( &$this, 'fnc_Make_Scripture'),  $arr_verse2->verse);	
			$temp2[$b] = '<div class="zef_compare_bibles">'.'<div class="zef_verse_number">'.$arr_verse2->verse_id.'</div><div class="zef_verse">'.$arr_verse2->verse.'</div></div>';
			$b++;				
		}
		for($x=1; $x< $a; $x++)
		{
			if ($x % 2)
			{
				$str_Chapter_Output  .= '<div class="odd">';
			}
			else
			{
				$str_Chapter_Output  .= '<div class="even">'; 
			}			
			for($y = 1; $y <= 2; $y++)
			{
				if($y %2 )
				{
					$str_Chapter_Output .= '<div class="zef_bible_1">'.$temp[$x].'</div>';
				}
				else
				{
					$str_Chapter_Output  .= '<div class="zef_bible_2">'.$temp2[$x].'</div>';
					if($item->flg_show_references)
					{
						foreach($item->arr_references as $obj_references)
						{
							if($obj_references->verse_id == $x)
							{
								$temp_link = 'bible='.$item->str_Bible_Version.'&book='.$item->int_Bible_Book_ID.'&chapter='.$item->int_Bible_Chapter.'&verse='.$x;
								$str_pre_link = '<a title="'. JText::_('COM_ZEFANIA_BIBLE_SCRIPTURE_BIBLE_LINK')." ".'" target="blank" href="index.php?view=references&option=com_zefaniabible&tmpl=component&'.$temp_link.'" class="modal" rel="{handler: \'iframe\', size: {x:'.$item->str_commentary_width.',y:'.$item->str_commentary_height.'}}">';
								$str_Chapter_Output  .= '<div class="zef_reference_hash">'.$str_pre_link.JText::_('ZEFANIABIBLE_BIBLE_REFERENCE_LINK').'</a></div>';	
								break;
							}
						}
					}				
					if($item->flg_show_commentary)
					{
						foreach($item->arr_commentary as $int_verse_commentary)
						{
							if($x == $int_verse_commentary->verse_id)
							{
								$str_commentary_url = JRoute::_("index.php?option=com_zefaniabible&view=commentary&com=".$item->str_commentary."&book=".$item->int_Bible_Book_ID."&chapter=".$item->int_Bible_Chapter."&verse=".$x."&tmpl=component");
								$str_Chapter_Output  .= '<div class="zef_commentary_hash"><a href="'.$str_commentary_url.'" class="modal" rel="{handler: \'iframe\', size: {x:'.$item->str_commentary_width.',y:'.$item->str_commentary_height.'}}">'.JText::_('ZEFANIABIBLE_BIBLE_COMMENTARY_LINK')."</a></div>";
							}
						}
					}						
				}
				
			}
			$str_Chapter_Output  .= '<div style="clear:both"></div></div>';			
		}
		return $str_Chapter_Output;
	}				
	public function fnc_Make_Scripture(&$arr_matches)
	{
		$this->flg_strong_dict = 1;
		$str_verse='';
		$temp = 'bible='.$this->item->str_curr_dict.'&book='.trim(strip_tags($arr_matches[0]));
		$str_verse = ' <a id="zef_strong_link" title="'. JText::_('COM_ZEFANIA_BIBLE_STRONG_LINK').'" target="blank" href="index.php?view=strong&option=com_zefaniabible&tmpl=component&'.$temp.'" class="modal" rel="{handler: \'iframe\', size: {x:'.$this->item->str_dictionary_width.',y:'.$this->item->str_dictionary_height.'}}">';		
		$str_verse .=  trim(strip_tags($arr_matches[0]));			
		$str_verse .= '</a> ';
		return $str_verse;
	}
	public function fnc_make_description($arr_chapter)
	{
		$str_descr = '';
		foreach ($arr_chapter as $arr_verse)
		{
			if($arr_verse->verse_id <= 1)
			{
				$str_descr .= " ".trim($arr_verse->verse);
			}
		}
		return $str_descr;
	}
	public function fnc_calcualte_day_diff($str_start_reading_date, $int_max_days)
	{
		// time zone offset.
 		$config = JFactory::getConfig();
		$JDate = JFactory::getDate('now', new DateTimeZone($config->get('offset')));
		$str_today = $JDate->format('Y-m-d', true);
		$arr_today = new DateTime($str_today);	
		$arr_start_date = new DateTime($str_start_reading_date);	
		$int_day_diff = round(abs($arr_today->format('U') - $arr_start_date->format('U')) / (60*60*24))+1;

		$int_verse_remainder = $int_day_diff % $int_max_days;
		if($int_verse_remainder == 0)
		{
			$int_verse_remainder = $int_max_days;
		}
		return $int_verse_remainder;
	}
	public function fnc_use_user_data($item)
	{
/*		if(($user->id > 0)and($flg_import_user_data))
		{
			$arr_user_data = $biblemodel->_buildQuery_getUserData($user->id);
			foreach($arr_user_data as $obj_user_data)
			{
				$str_start_reading_date = $obj_user_data->reading_start_date;
				$str_bibleVersion = $obj_user_data->bible_alias;
				$str_reading_plan = $obj_user_data->plan_alias;
			}
		}		*/	
	}
	public function fnc_output_dual_reading_plan($item)
	{
			$book = 0;
			$chap = 0;
			$x = 1;
			$y = 1;		
			$str_chapter = '';
		
			foreach($item->arr_plan as $reading)
			{
				$cnt_verse_count = count($reading);
				$z = 1;
				foreach($reading as $plan)
				{	
					if (($plan->book_id > $book)or($plan->chapter_id > $chap))
					{
						$book = $plan->book_id;
						$chap = $plan->chapter_id;
						if($y > 1)
						{
							$str_chapter .=  '</div>';
						}
						$str_chapter .=  '<div class="zef_bible_Header_Label_Plan"><h1 class="zef_bible_Header_Label_h1"><a name="'.$y.'" id="'.$y.'"></a>'.JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$plan->book_id)." ";
						$str_chapter .=  mb_strtolower(JText::_('ZEFANIABIBLE_BIBLE_CHAPTER'),'UTF-8')." ".$plan->chapter_id.'</h1></div>';
						$str_chapter .=  '<div class="zef_bible_Chapter">';
						$arr_single_commentary  = $arr_commentary[($y-1)];
						if($item->flg_show_audio_player)
						{
							$obj_player = $mdl_audio->fnc_audio_player($item->str_Bible_Version,$plan->book_id,$plan->chapter_id, $y);
							$str_chapter .=  '<div class="zef_player-'.$y.'">';
							$str_chapter .=  $obj_player;
        					$str_chapter .=  "</div>";
							$str_chapter .=  '<div style="clear:both"></div>';
						}
						$x = 1;
						$y++;			
					}

					if ($x % 2)
					{
						$str_chapter .=  '<div class="odd">';
					}
					else
					{
						$str_chapter .=  '<div class="even">'; 
					}
					$str_match_fuction = "/(?=\S)([HG](\d{1,4}))/iu";
					$plan->verse = preg_replace_callback( $str_match_fuction, array( &$this, 'fnc_Make_Scripture'),  $plan->verse);
					
					$str_chapter .=  "<div class='zef_verse_number'>".$plan->verse_id."</div><div class='zef_verse'>".$plan->verse."</div>";
					if($item->flg_show_references)
					{
						foreach($arr_references as $obj_references)
						{
							if(($plan->book_id == $obj_references->book_id)and($plan->chapter_id == $obj_references->chapter_id)and($plan->verse_id == $obj_references->verse_id))
							{
								$temp = 'bible='.$item->str_Bible_Version.'&book='.$plan->book_id.'&chapter='.$plan->chapter_id.'&verse='.$plan->verse_id;
								$str_pre_link = '<a title="'. JText::_('COM_ZEFANIA_BIBLE_SCRIPTURE_BIBLE_LINK')." ".'" target="blank" href="index.php?view=references&option=com_zefaniabible&tmpl=component&'.$temp.'" class="modal" rel="{handler: \'iframe\', size: {x:'.$item->str_commentary_width.',y:'.$item->str_commentary_height.'}}">';
								$str_chapter .=  '<div class="zef_reference_hash">'.$str_pre_link.JText::_('ZEFANIABIBLE_BIBLE_REFERENCE_LINK').'</a></div>';									
								break;
							}
						}							
					}
					if($item->flg_show_commentary)
					{
						foreach($arr_single_commentary as $int_verse_commentary)
						{
							if($plan->verse_id == $int_verse_commentary->verse_id)
							{
								$str_commentary_url = JRoute::_("index.php?option=com_zefaniabible&view=commentary&com=".$this->str_commentary."&book=".$plan->book_id."&chapter=".$plan->chapter_id."&verse=".$plan->verse_id."&tmpl=component");
								$str_chapter .=  '<div class="zef_commentary_hash"><a href="'.$str_commentary_url.'" class="modal" rel="{handler: \'iframe\', size: {x:'.$item->str_commentary_width.',y:'.$item->str_commentary_height.'}}">'.JText::_('ZEFANIABIBLE_BIBLE_COMMENTARY_LINK')."</a></div>";
							}
						}
					}	
					$str_chapter .=  '<div style="clear:both"></div></div>';		
					$x++;
					$z++;
				}
			}
		return $str_chapter;
	}	
	public function fnc_jump_button($item)
	{
		$int_today = $item->int_day_diff;
		$str_other_url_var = '';
		$str_plan_start_date = date('d-m-Y', strtotime("-".$int_today." day"));
				
		echo '<select name="jump" id="zef_day_jump" class="inputbox" onchange="javascript:location.href = this.value;">';
		for($x = 1; $x <= $item->int_max_days; $x++)
		{
			if(($item->flg_show_commentary)and(count($item->arr_commentary_list) > 1))
			{
					$str_other_url_var .= "&com=".$this->str_commentary;
			}
			if($item->str_tmpl == "component")
			{
				$str_other_url_var .=  "&tmpl=component";
			}
			if(($item->flg_show_dictionary)and(count($item->arr_dictionary_list) > 1))
			{
				$str_other_url_var .=  "&dict=".$this->str_curr_dict;
			}
			if($item->flg_use_strong ==1)
			{
				$str_other_url_var .= "&strong=".$item->flg_use_strong;
			}			
			$str_url = "index.php?option=com_zefaniabible&plan=".$item->str_reading_plan."&bible=".$item->str_Bible_Version."&view=".$item->str_view."&day=".$x.$str_other_url_var;
			$str_url = JRoute::_($str_url);
			echo '	<option value="'.$str_url.'"';			

			if($x == $item->int_day_number )
			{
				echo 'selected';
			}
			echo  '>'.JText::_('ZEFANIABIBLE_READING_PLAN_DAY').' '.$x;
			if($x == $int_today)
			{
				echo " - " .JText::_('COM_ZEFANIABIBLE_TODAY');
			}
			else
			{
				echo " - " .date('d/m/Y', strtotime($str_plan_start_date. "+".$x." day"));
			}
			echo '</option>';
		}
		echo '</select>';
	}	
}
?>