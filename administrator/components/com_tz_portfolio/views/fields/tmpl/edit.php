<?php
/*------------------------------------------------------------------------

# TZ Portfolio Extension

# ------------------------------------------------------------------------

# author    DuongTVTemPlaza

# copyright Copyright (C) 2012 templaza.com. All Rights Reserved.

# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL

# Websites: http://www.templaza.com

# Technical Support:  Forum - http://templaza.com/Forum

-------------------------------------------------------------------------*/

//no direct access
defined('_JEXEC') or die('Restricted access');
$fields = $this -> defvalue;
JHtml::_('behavior.tooltip');
JHtml::_('formbehavior.chosen', 'select');
?>

<script type="text/javascript ">
    Joomla.submitbutton = function(pressbutton) {
        var form = document.adminForm;

        if (pressbutton == 'cancel') {
            document.location='index.php?option=com_tz_portfolio&view=fields';
            return true;
        }

        var total = 0;
        for(i=0;i<form.fieldsgroup.length;i++){
            if(form.fieldsgroup[i].selected){
                total++;
            }

        }
        // do field validation
        if(form.title.value==""){
            alert( "<?php echo JText::_( 'COM_TZ_PORTFOLIO_INPUT_FIELD_TITLE', true ); ?>" );
            form.title.focus();
        }
        else if(form.fieldsgroup.value==-1 && total==1){
            alert( "<?php echo JText::_( 'COM_TZ_PORTFOLIO_INPUT_FIELD_SELECT_GROUP', true ); ?>" );
            form.fieldsgroup.focus();
        }
        else if(form.type.value==0){
            alert( "<?php echo JText::_( 'COM_TZ_PORTFOLIO_FIELD_SELECT_TYPE', true ); ?>" );
            form.type.focus();
        }
        else {
            submitform( pressbutton);
        }
    }

    window.addEvent('load', function() {
        var createBox = function(object,name,tz_count,imageurl){
            var myDiv = new Element('div',{ class: 'clearfix'}).inject(object);
            var myDiv = new Element('div',{ style:"clear:both;display: block; margin-top: 10px;",class: 'input-prepend input-append'}).inject(object);
            var tz_e = location.href.match(/^(.+)administrator\/index\.php.*/i)[1];

            var icon = new Element('div',{
                class: 'add-on',
                html: '<\i class="icon-eye"></i>'
            }).inject(myDiv);
            var tz_a = new Element('input',{
                type:"text",
                class:"inputbox image-select",
                name: name,
                value:imageurl,
                id:"image-select-"+tz_count,
                readonly:'true',
                style:"width:200px;"
            });
            tz_a.inject(myDiv);
            var tz_d = "image-select-" + tz_count,
                tz_b = (new Element("a", {
                    class: "btn",
                    "id": "tz_img_button"+tz_count
                })).set('html', "<\i class=\"icon-file\"></i>&nbsp;<?php echo JText::_('COM_TZ_PORTFOLIO_BROWSE_SERVER');?>").inject(tz_a,'after'),
                tz_f = (new Element("a", {
                    class: 'btn',
                    "name": "tz_img_cancel_"+tz_count,
                    html:'<i class="icon-refresh"></i>&nbsp;<?php echo JText::_('COM_TZ_PORTFOLIO_RESET');?>'
                })).inject(tz_b,'after'),
                tz_g = (new Element("div", {
                    "class": "tz-image-preview",
                    "style": "clear:both;max-width:300px"
                })).inject(tz_f,'after');

            tz_a.setProperty("id", tz_d);
            tz_a.getProperty("value") && (new Element("img", {
                src: tz_e + tz_a.getProperty("value"),
                style:'max-width:150px'
            })).inject(tz_g,'inside');
            tz_f.addEvent("click", function (e) {
                e.stop();
                tz_a.setProperty("value", "");
                tz_a.getParent().getElement("div.tz-image-preview").empty()
            });

            tz_b.addEvent("click", function (h) { (h).stop();
                SqueezeBox.fromElement(this, {
                    handler: "iframe",
                    url: "index.php?option=com_media&view=images&tmpl=component&e_name=" + tz_d,
                    size: {
                        x: 800,
                        y: 500
                    }
                });

                window.jInsertEditorText = function (text, editor) {
                    if (editor.match(/^image-select-/)) {
                        var d = $(editor),
                        tz_b = d.getParent().getElement("div.tz-image-preview").set('html',text ).getElement("img");
                        d.setProperty("value", tz_b.getProperty('src'));
                        tz_b.setProperty("src", tz_e + tz_b.getProperty("src"));
                    } else tinyMCE.execInstanceCommand(editor, 'mceInsertContent',false,text);
                };

            });
            return myDiv;
        };
        var renderElement=function(tz_count_gb){
            
            $('fields').empty();
            var optionFields = function(){
                var tz_count = 0;
                var myButton = new Element('a', {
                    class: 'btn',
                    html: '<i class="icon-plus"></i>&nbsp;<?php echo JText::_('COM_TZ_PORTFOLIO_ADD_NEW');?>',
                    events: {
                        click: function(e){
                            e.stop();

                            var myDiv = new Element('div',{
                                styles:{
                                    display:'block',
                                    width:'100%',
                                    float:'left',
                                    'padding-top':'25px'
                                }
                            });
                            myDiv.inject($('fieldvalue'));
                            //value
                            var myValue = new Element('input',{
                                type: 'text',
                                'name': 'option_name[]'
                            });
                            myValue.inject(myDiv);

                            var myRemove = new Element('a',{
                                class: 'btn',
                                html:'<i class="icon-remove"></i>&nbsp;<?php echo JText::_('COM_TZ_PORTFOLIO_REMOVE');?>',
                                events:{
                                    click: function(e){
                                        e.stop();
                                        myDiv.dispose();
                                        myValue.dispose();
                                        myDefault.dispose();
                                        myRemove.dispose();
                                        myBox.dispose();
                                        tz_count--;
                                    }
                                }
                            });
                            myRemove.inject(myDiv);

                            if($('type').value == 'multipleSelect' || $('type').value == 'checkbox'){
                                var myDefault = new Element('div',{
                                    style:"display:inline-block; padding-left:10px; width:20%;",
                                    html:'<\input type="checkbox" name="default[]" value="'+tz_count+'" \style="margin:0;"/><\span style="padding-left:5px; font-size: 11px;">'+
                                            '<i><?php echo JText::_('COM_TZ_PORTFOLIO_DEFAULT_VALUES');?></i></span>'
                                }).inject(myRemove,'after');
                            }
                            if($('type').value == 'select' || $('type').value == 'radio'){
                                var myDefault = new Element('div',{
                                    style:"display:inline-block; padding-left:10px; width:20%;",
                                    html:'<\input type="radio" name="default[]" value="'+tz_count+'" \style="margin:0;"/><\span style="padding-top:5px; font-size: 11px;">'+
                                            '<i><?php echo JText::_('COM_TZ_PORTFOLIO_DEFAULT_VALUE');?></i></span>'
                                }).inject(myRemove,'after');
                            }

                            var myBox = createBox($('fieldvalue'),'option_icon[]',tz_count+1,'');


                            tz_count++;
                        }
                    }
                });
                myButton.inject($('fieldvalue'));
                <?php
                $i=0;
                foreach($fields as $value):
                ?>
                    var type        = '<?php echo $fields[0] -> type;?>';
                    var optionName  = '';
                    var image       = '';
                    if(type == $('type').value){
                        optionName  = '<?php echo $fields[$i] -> name;?>';
                        image       = '<?php echo $fields[$i] -> image;?>';
                    }

                    var myDiv = new Element('div',{
                        styles:{
                            float:'left',
                            width:'100%',
                            'padding-top':'25px'
                        }
                    });
                    myDiv.inject($('fieldvalue'));
                    var myValue = new Element('input',{
                        type: 'text',
                        'name': 'option_name[]',
                        value:optionName
                    });
                    myValue.inject(myDiv);
                    var myRemove = new Element('a',{
                            class: 'btn',
                            html:'<i class="icon-remove"></i>&nbsp;<?php echo JText::_('COM_TZ_PORTFOLIO_REMOVE');?>',
                            events:{
                                click: function(e){
                                    e.stop();
                                    myDiv.dispose();
                                    myValue.dispose();
                                    myRemove.dispose();
                                    myBox.dispose();
                                    tz_count--;
                                }
                            }
                        });
                        myRemove.inject(myDiv);

                    if($('type').value == 'multipleSelect' || $('type').value == 'checkbox'){
                        var myDefault = new Element('div',{
                            style:"display:inline-block; padding-left:10px; width:20%;",
                            html:'<input type="checkbox" name="default[]" value="<?php echo $i;?>"'+
                                    '<?php if(in_array($i,$fields[$i] -> default_value)) echo ' checked="checked"';?>'+
                                    ' \style="margin:0;"/><\span style="padding-left:5px; font-size: 11px;">'+
                                    '<i><?php echo JText::_('COM_TZ_PORTFOLIO_DEFAULT_VALUES');?></i></span>'
                        }).inject(myRemove,'after');
                    }
                
                    if($('type').value == 'select' || $('type').value == 'radio'){
                        var myDefault = new Element('div',{
                            style:"display:inline-block; padding-left:10px; width:20%;",
                            html:'<input type="radio" name="default[]" value="<?php echo $i;?>"'+
                                    '<?php if(in_array($i,$fields[$i] -> default_value)) echo ' checked="checked"';?>'
                                    +' \style="margin:0;"/><span style="padding-left:5px; font-size: 11px;">'+
                                    '<i><?php echo JText::_('COM_TZ_PORTFOLIO_DEFAULT_VALUE');?></i></span>'
                        }).inject(myRemove,'after');
                    }

                    var myBox = createBox($('fieldvalue'),'option_icon[]',<?php echo $i+1;?>,image);
            
                    tz_count++;
                <?php $i++;?>
                <?php endforeach;?>
            };

            var myField =   new Element('div', {
                id : 'fieldvalue'
            });
            myField.inject($('fields'));
            switch (document.adminForm.type.value) {
                case 'textfield':
                    var myField = new Element('input', {
                        type: 'text',
                        'name': 'option_value[]',
                        value: '<?php echo ($fields[0] -> type == 'textfield')?$fields[0] -> name:'';?>'
                    });
                    myField.inject($('fieldvalue'));
                    var myDefault   = new Element('div',{
                        style:"font-size:11px; padding-top:5px;",
                        html:'<i><?php echo JText::_('COM_TZ_PORTFOLIO_DEFAULT_VALUE');?></i>'
                    }).inject(myField,'after');
                    createBox($('fieldvalue'),'option_icon[]',0,'<?php echo ($fields[0] -> type == 'textfield')?$fields[0] -> image:'';?>');
                    break;
                case 'multipleSelect':
                case 'checkbox':
                case 'radio':
                case 'select':
                        optionFields();
                    break;
                case 'textarea':
                    var myDefault   = new Element('div',{
                        style:"font-size:11px; padding-top:5px;",
                        html:'<i><?php echo JText::_('COM_TZ_PORTFOLIO_DEFAULT_VALUE');?></i>'
                    }).inject($('fieldvalue'));
                    var myField = new Element('textarea',{
                        'name':'option_value[]',
                        styles:{
                            display:'block',
                            width: '300px',
                            height: '100px'
                        },
                        value:'<?php echo ($fields[0] -> type == 'textarea')?$fields[0] -> name:'';?>'
                    });
                    myField.inject($('fieldvalue'));
                    /*var myDiv = new Element('div',{
                        html:<?php echo '\'<strong><i>'.JText::_('Use editor').'</i></strong>\'';?>,
                        styles:{
                            display:'block',
                            float:'left',
                            width:'100%'
                        }
                    });
                    myDiv.inject($('fieldvalue'));
                    var myField = new Element('input',{
                       type:'checkbox',
                       value : '1',
                       'name':'option_editor',
                        checked:'<?php echo ($fields[0]-> editor == '1')?'checked':'';?>'
                    });
                    myField.inject(myDiv);*/
                    createBox($('fieldvalue'),'option_icon[]',0,'<?php echo ($fields[0] -> type == 'textarea')?$fields[0] -> image:'';?>');
                    break;
                case 'link':
                        var myDefault   = new Element('div',{
                            style:"font-size:11px; padding-top:5px;",
                            html:'<label></label><i><?php echo JText::_('COM_TZ_PORTFOLIO_DEFAULT_VALUES');?></i>'
                        }).inject($('fieldvalue'));
                        var linkDiv = new Element('div',{});
                        linkDiv.inject($('fieldvalue'));
                        var myLabel = new Element('label',{
                           html:'<strong><?php echo JText::_('COM_TZ_PORTFOLIO_LINK_TEXT');?></strong>',
                            styles:{
                                'font-size':'11px'
                            }
                        });
                        myLabel.inject(linkDiv);
                        var myField = new Element('input',{
                            type: 'text',
                            'name':'option_name[]',
                            value:'<?php echo ($fields[0] -> type == 'link')?$fields[0] -> name:'';?>'
                        });
                        myField.inject(linkDiv);
                        var linkDiv = new Element('div',{});
                        linkDiv.inject($('fieldvalue'));
                        var myLabel = new Element('label',{
                           html:'<strong><?php echo JText::_('COM_TZ_PORTFOLIO_LINK_URL');?></strong>',
                            styles:{
                                'font-size':'11px'
                            }
                        });
                        myLabel.inject(linkDiv);
                        var myField = new Element('input',{
                            type: 'text',
                            'name':'option_value[]',
                            value:'<?php echo ($fields[0] -> type == 'link')?$fields[0] -> value:'';?>'
                        });
                        myField.inject(linkDiv);
                        var linkDiv = new Element('div',{});
                        linkDiv.inject($('fieldvalue'));
                        var myLabel = new Element('label',{
                           html:'<strong><?php echo JText::_('COM_TZ_PORTFOLIO_OPEN_IN');?></strong>',
                            styles:{
                                'font-size':'11px'
                            }
                        });
                        myLabel.inject(linkDiv);
                        var myField = new Element('select',{
                            html:'<option value="_self" <?php echo ($fields[0] -> target == '_self')?' selected="selected"':'';?>><?php echo JText::_('Same window');?></option>'
                                    +'<option value="_blank"<?php echo ($fields[0] -> target == '_blank')?'selected="selected"':'';?>><?php echo JText::_('New window');?></option> ',
                            'name':'option_target[]'
                        });
                        myField.inject(linkDiv);
                        createBox($('fieldvalue'),'option_icon[]',0,'<?php echo $fields[0] -> image;?>');
                        jQuery('select').chosen({
                            disable_search_threshold : 10,
                            allow_single_deselect : true
                        });
                    break;
                default:
                    $('fields').set('html', '<label><?php echo JText::_('COM_TZ_PORTFOLIO_OPTION_FIELD_VALUES_DESC');?></label>');
                    break;
            }
        }

        renderElement(0);

//        $('type').addEvent('change', function (e) {
//            var tz_count_gb = 0;
//            renderElement(tz_count_gb);
//        });

        $$('.chzn-drop li').addEvent('click',function(e){
            e.stop();
            var tz_count_gb = 0;
            renderElement(tz_count_gb);
        })
    });
</script>
<form name="adminForm" method="post" action="index.php?option=<?php echo $this -> option;?>&view=<?php echo $this -> view;?>">

    <!-- Begin Content -->
    <div class="span10 form-horizontal">
        <fieldset class="adminform">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#details" data-toggle="tab"><?php echo JText::_('JDETAILS');?></a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="details">
                    <div class="control-group">
                        <div class="control-label">
                            <label width="100" for="title" class="hasTip"
                                    title="<?php echo JText::_('COM_TZ_PORTFOLIO_LABEL_TITLE')?>::<?php echo JText::_('COM_TZ_PORTFOLIO_LABEL_TITLE')?>">
                                <?php echo JText::_('COM_TZ_PORTFOLIO_LABEL_TITLE')?>
                                <span class="star"> *</span>
                            </label>
                        </div>
                        <div class="controls">
                            <input type="text" title="Title" maxlength="50"
                               size="50"
                               value="<?php echo $this -> listsEdit[0] -> title;?>"
                               id="title" name="title"/>
                        </div>
                    </div>
                    <div class="control-group">
                        <div class="control-label">
                            <label width="100" for="fieldsgroup" class="hasTip"
                                   title="<?php echo JText::_('COM_TZ_PORTFOLIO_FIELDS_GROUP_REQUIRED')?>::<?php echo JText::_('COM_TZ_PORTFOLIO_FIELDS_GROUP_REQUIRED_DESC')?>">
                                <?php echo JText::_('COM_TZ_PORTFOLIO_FIELDS_GROUP_REQUIRED')?>
                                <span class="star"> *</span>
                            </label>
                        </div>
                        <div class="controls">
                            <select multiple="multiple" size="10" id="fieldsgroup" name="fieldsgroup[]" style="width:150px;">
                                <option value="-1">
                                    <?php echo JText::_('COM_TZ_PORTFOLIO_OPTION_SELECT_GROUP');?>
                                </option>
                                <?php
                                if(count($this -> listsGroup)>0){
                                    $i=0;
                                    foreach($this -> listsGroup as $row){
                                ?>
                                <option value="<?php echo $row -> id;?>"
                                    <?php
                                    for($k=0;$k<count($this -> listsEdit);$k++){
                                        if($row -> id == $this -> listsEdit[$k] -> groupid){
                                            echo ' selected="selected"';
                                        }
                                    }
                                    ?>
                                >
                                    <?php echo $row -> name;?>
                                </option>
                                <?php
                                            $i++;
                                        }
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="control-group">
                        <div class="control-label">
                            <label width="100" for="type" class="hasTip"
                                   title="<?php echo JText::_('COM_TZ_PORTFOLIO_TYPE')?>::<?php echo JText::_('COM_TZ_PORTFOLIO_TYPE_DESC')?>">
                                <?php echo JText::_('COM_TZ_PORTFOLIO_TYPE')?>
                                <span class="star"> *</span>
                            </label>
                        </div>
                        <div class="controls">
                            <select name="type" id="type">
                                <option value="0"><?php echo JText::_('COM_TZ_PORTFOLIO_OPTION_SELECT_TYPE');?></option>
                                <option value="textfield"<?php echo ($this -> listsEdit[0] -> type == 'textfield')?' selected="selected"':'';?>>
                                    <?php echo JText::_('COM_TZ_PORTFOLIO_TEXT_FIELD');?>
                                </option>
                                <option value="textarea"<?php echo ($this -> listsEdit[0] -> type == 'textarea')?' selected="selected"':'';?>>
                                    <?php echo JText::_('COM_TZ_PORTFOLIO_TEXTAREA');?>
                                </option>
                                <option value="select"<?php echo ($this -> listsEdit[0] -> type == 'select')?' selected="selected"':'';?>>
                                    <?php echo JText::_('COM_TZ_PORTFOLIO_DROP_DOWN_SELECTION');?>
                                </option>
                                <option value="multipleSelect"<?php echo (strtolower($this -> listsEdit[0] -> type) == 'multipleselect')?' selected="selected"':'';?>>
                                    <?php echo JText::_('COM_TZ_PORTFOLIO_MULTI_SELECT_LIST');?>
                                </option>
                                <option value="radio"<?php echo ($this -> listsEdit[0] -> type == 'radio')?' selected="selected"':'';?>>
                                    <?php echo JText::_('COM_TZ_PORTFOLIO_RADIO_BUTTONS');?>
                                </option>
                                <option value="checkbox"<?php echo ($this -> listsEdit[0] -> type == 'checkbox')?' selected="selected"':'';?>>
                                    <?php echo JText::_('COM_TZ_PORTFOLIO_CHECK_BOX');?>
                                </option>
                                <option value="link"<?php echo ($this -> listsEdit[0] -> type == 'link')?' selected="selected"':'';?>>
                                    <?php echo JText::_('COM_TZ_PORTFOLIO_LINK');?>
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="control-group">
                        <div class="control-label">
                            <label width="100" for="defaultvalue">
                                <?php echo JText::_('COM_TZ_PORTFOLIO_OPTION_FIELD_VALUES')?>:
                            </label>
                        </div>
                        <div class="controls" id="fields">
                            <label><?php echo JText::_('COM_TZ_PORTFOLIO_OPTION_FIELD_VALUES_DESC');?></label>
                        </div>
                    </div>
                    <div class="control-group">
                        <div class="control-label">
                            <label width="100" for="description">
                                <?php echo JText::_('COM_TZ_PORTFOLIO_DESCRIPTION');?>
                            </label>
                        </div>
                        <div class="controls">
                            <?php echo $this -> editor -> display('description',htmlspecialchars_decode($this -> listsEdit[0] -> description),'100%', '300', '60', '20', array('pagebreak', 'readmore'));?>
                        </div>
                    </div>


                    
                </div>
            </div>
        </fieldset>
    </div>
    <!-- End Content -->
    <!-- Begin Sidebar -->
	<div class="span2">
		<h4><?php echo JText::_('JDETAILS');?></h4>
        <fieldset class="form-vertical">
            <div class="control-group">
                <label width="100" for="published">
                    <?php echo JText::_('JPUBLISHED')?>:
                </label>
                <div class="controls">
                    <?php $state    = $this -> listsEdit[0] -> published == 1?'P':'U';?>
                    <?php echo JHtml::_('grid.state',$state,'JPUBLISHED','JUNPUBLISHED');?>
                </div>
            </div>
        </fieldset>
    </div>
    <!-- End Sidebar -->
    <input type="hidden" value="<?php echo $this -> option;?>" name="option">
    <input type="hidden" value="<?php $cid=JRequest::getInt('id'); echo $cid;?>" name="id">
    <input type="hidden" value="" name="task">
    <?php echo JHTML::_('form.token');?>
</form>