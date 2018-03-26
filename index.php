<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
  <meta http-equiv="content-type" content="text/html; charset=utf-8">
  <meta name="generator" content="PSPad editor, www.pspad.com">
  <title>Badminton Atlas</title>
  <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">

  <script src="https://code.jquery.com/jquery-3.1.0.min.js" type="text/javascript" language="JavaScript"></script>
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.0/themes/redmond/jquery-ui.min.css" type="text/css" />
  <script src="https://code.jquery.com/ui/1.12.0/jquery-ui.min.js" type="text/javascript" language="JavaScript"></script>

  <script src="jtable.2.4.0/jquery.jtable.min.js" type="text/javascript" language="JavaScript"></script>
  <!--link href="jtable.2.4.0/themes/metro/blue/jtable.min.css" rel="stylesheet" type="text/css" /-->
  <link href="jtable.2.4.0/themes/jqueryui/jtable_jqueryui.min.css" rel="stylesheet" type="text/css" />
  
<script type="text/javascript" language="JavaScript">
<!--
 
$.datepicker.regional['cs'] = { 
    closeText: 'Zavřít', 
    prevText: 'Předchozí', 
    nextText: 'Další', 
    currentText: 'Dnes', 
    monthNames: ['leden','únor','březen','duben','květen','červen', 'červenec','srpen','září','říjen','listopad','prosinec'],
    monthNamesShort: ['Le','Ún','Bř','Du','Kv','Čn', 'Čc','Sr','Zá','Ří','Li','Pr'], 
    dayNames: ['Neděle','Pondělí','Úterý','Středa','Čtvrtek','Pátek','Sobota'], 
    dayNamesShort: ['Ne','Po','Út','St','Čt','Pá','So',], 
    dayNamesMin: ['Ne','Po','Út','St','Čt','Pá','So'], 
    weekHeader: 'Ty', 
    dateFormat: 'dd.mm.yy', 
    firstDay: 1, 
    isRTL: false, 
    showMonthAfterYear: false, 
    yearSuffix: ''}; 
$.datepicker.setDefaults($.datepicker.regional['cs']);
                

  function setCookie(key, value) { var expireDate = new Date(); expireDate.setDate(expireDate.getDate() + 30); document.cookie = key + '=' + value +'; expires=' + expireDate.toUTCString(); }
  function getCookie(key) { var keyValue = document.cookie.match('(^|;) ?' + key + '=([^;]*)(;|$)'); return keyValue ? keyValue[2] : null; }

  function isAdmin() { return getCookie("uname") == "admin"; }
  function setUcastnik(uname) {$("#span_ucast").html(uname?"(Účastník: "+uname+")":"");}
  function checkOps(elem) {
      var uname = getCookie("uname");
      var tid = elem.attr("id").match("ucast_(.+)")[1];
  }
  function checkIsAdmin() {
      var jt = $('#terminTableContainer');
      if (isAdmin()) {
          $(jt.find('.jtable-toolbar-item')[2]).show();
          //jt.jtable('changeColumnVisibility','used','hidden');
          jt.jtable('changeColumnVisibility','remove','visible');
      } else {
          $(jt.find('.jtable-toolbar-item')[2]).hide();
          //jt.jtable('changeColumnVisibility','used','visible');
          jt.jtable('changeColumnVisibility','remove','hidden');
      } 
      jt.jtable('changeColumnVisibility','used','visible');
  }
  function checkRegTermin(termin) {
      var d = new Date(termin);
      d.setHours(10);
      d.setDate(d.getDate() - d.getDay() + 1);
      return d <= new Date();
  }
  function saveName(ahref) {
      var uname = $("#edit_uname").val();
      setCookie("uname", uname);
      //$("[id^='ucast_']").each(function(index) { checkOps($(this)); });
      //if (isAdmin()) $('#terminTableContainer').find('.jtable-toolbar-item.jtable-toolbar-item-add-record').remove();
      checkIsAdmin();
      setUcastnik(uname);
      $('#terminTableContainer').jtable('reload');
  }
  function addUcast(tid) {
      $.post("operace.php", {op:"addUcast",tid:tid}, function (data) {
          if (data.Result='OK')
              $("#terminTableContainer").jtable('reload');
          else
              alert(data.Message);
      },'json');    
  }
  function delUcast(tid) {
      $.post("operace.php", {op:"delUcast",tid:tid}, function (data) {
          if (data.Result='OK')
              $("#terminTableContainer").jtable('reload');
          else
              alert(data.Message);
      },'json');    
  }
  function editUcast(tid) {
  
  }
  function addDate() {
      var edate = $("#hide_date").val();
      $.post("operace.php", {op:"addDate",termin:edate}, function (data) {
          if (data.Result='OK')
              $("#terminTableContainer").jtable('reload');
          else
              alert(data.Message);
      },'json');    
  }
  function delDate(tid) {
      $.post("operace.php", {op:"delDate",tid:tid}, function (data) {
          if (data.Result='OK')
              $("#terminTableContainer").jtable('reload');
          else
              alert(data.Message);
      },'json');    
  }
  function refreshTable() {
      $("#terminTableContainer").jtable('reload');
      setTimeout(refreshTable, 600000);
  }
  $( document ).ready(function() {
      var uname = getCookie("uname");
      $("#edit_uname").val(uname);
      $("#terminTableContainer").jtable({
          title: 'Badminton Atlas <span id="span_ucast"></span>',
          messages: {
              addNewRecord: 'Přidat termín',
              deleteConfirmation: 'Tento termín bude smazán. Jste jsi jistý?',
              deleteText: 'Smazat termín',
              deleting: 'Mazání termínu',
              canNotDeletedRecords: 'Nelze smazat {0} z {1} termínů!',
              deleteProggress: 'Mazání {0} of {1} termínů, probíhá...'
          },
          jqueryuiTheme: true,
          columnSelectable: false,
          actions:{listAction: "operace.php"},
          fields: {
              tid:{key:true,create:false,edit:false,list:false},
              termin:{title:"Termín",width:"20%",type:"date",displayFormat:"D dd. MM yy",edit:false},
              ucast:{title:"Účast",width:"70%",create:false},
              pocet:{title:"Počet",width:"10%",create:false},
              used: {
                  create: false,
                  display: function(data) {
                      var $btnc = $('<button></button>').button({icon:'ui-icon-check'})
                          .addClass('cmd-add-ucast')
                          .prop('title','Přidat účast')
                          .click(function(e){
                              e.preventDefault();
                              e.stopPropagation();
                              $(e.delegateTarget).button("disable");
                              addUcast(data.record.tid);
                          });
                      var $btnu = $('<button></button>').button({icon:'ui-icon-closethick'})
                          .addClass('cmd-del-ucast')
                          .prop('title','Odebrat účast')
                          .click(function(e){
                              e.preventDefault();
                              e.stopPropagation();
                              $(e.delegateTarget).button("disable");
                              delUcast(data.record.tid);
                          });
                      var $btne = $('<button></button>').button({icon:'ui-icon-pencil'})
                          .addClass('cmd-edit-ucast')
                          .prop('title','Upravit účast')
                          .click(function(e){
                              e.preventDefault();
                              e.stopPropagation();
                              //$(e.delegateTarget).button("disable");
                              //editUcast(data.record.tid);
                              
                              $("#terminTableContainer").jtable('openChildTable', $btne.closest('tr'),
                                  {
                                      title: 'Účast na termínu',
                                      messages: {
                                          addNewRecord: 'Přidat účastníka',
                                          deleteConfirmation: 'Tento účastník bude smazán. Jste jsi jistý?',
                                          deleteText: 'Smazat účastníka',
                                          deleting: 'Mazání účastníka',
                                          canNotDeletedRecords: 'Nelze smazat {0} z {1} účastníků!',
                                          deleteProggress: 'Mazání {0} of {1} účastníků, probíhá...'
                                      },
                                      jqueryuiTheme: true,
                                      columnSelectable: false,
                                      actions: {listAction: "ucast.php?tid=" + data.record.tid,
                                          createAction: "ucast.php?op=addUcast&tid=" + data.record.tid,
                                          deleteAction: "ucast.php?op=delUcast"},
                                      fields: {uid: {key:true,create:false,edit:false,list:false},
                                          ucastnik: {title:"Účastník",width:"90%",create:true}}
                                  }, function(data) {
                                      data.childTable.jtable('load');
                                  });
                              
                              
                          });
                      return $('<span />').append($btnc).append($btnu).append($btne);
                  }
              },
              remove: {
                  create: false,
                  display: function(data) {
                      var $btnd = $('<button></button>').button({icon:'ui-icon-trash'})
                          .prop('title','Smazat termín')
                          .click(function(e){
                              e.preventDefault();
                              e.stopPropagation();
                              $(e.delegateTarget).button("disable");
                              delDate(data.record.tid);
                          });
                      return $('<span />').append($btnd);
                  }
              }
          },
          toolbar:{items:[{icon:"ui-icon-refresh",uiIcon:true,text:"Obnovit",click:function(e){$("#terminTableContainer").jtable('reload');}},
              {icon:"ui-icon-person",uiIcon:true,text:"Změnit účastníka",click:function(e){$("#change_uname").dialog("open");}},
              {icon:"ui-icon-plusthick",uiIcon:true,text:"Přidat termín",click:function(e){$("#add_date").dialog("open");}}]},
          rowInserted: function(event, data){
              var uname = getCookie('uname');
              if (isAdmin()) {
                  data.row.find('.cmd-add-ucast').hide();
                  data.row.find('.cmd-del-ucast').hide();
                  data.row.find('.cmd-edit-ucast').show();
              } else {
                  data.row.find('.cmd-edit-ucast').hide(); 
              
                  if (!uname || checkRegTermin(data.record.termin)) {
                      data.row.find('.cmd-add-ucast').hide();
                      data.row.find('.cmd-del-ucast').hide();
                  } else
                  if (data.record.ucast && $.inArray(uname,data.record.ucast.split(', ')) > -1) {
                      data.row.find('.cmd-add-ucast').hide();
                      data.row.find('.cmd-del-ucast').show();
                  } else {
                      data.row.find('.cmd-add-ucast').show();
                      data.row.find('.cmd-del-ucast').hide();
                  }
              }
          }
      });
      $("#terminTableContainer").jtable("load");
      
      $("#ucastTableContainer").jtable({
          title: 'Účast na termínu <span id="span_ucast_termin"></span>',
          messages: {
              addNewRecord: 'Přidat účastníka',
              deleteConfirmation: 'Tento účastník bude smazán. Jste jsi jistý?',
              deleteText: 'Smazat účastníka',
              deleting: 'Mazání účastníka',
              canNotDeletedRecords: 'Nelze smazat {0} z {1} účastníků!',
              deleteProggress: 'Mazání {0} of {1} účastníků, probíhá...'
          },
          jqueryuiTheme: true,
          columnSelectable: false,
          actions:{listAction: "ucast.php"},
          fields: {
              uid:{key:true,create:false,edit:false,list:false},
              ucast:{title:"Účastník",width:"90%",create:false},
              used: {
                  create: false,
                  display: function(data) {
                      var $btnu = $('<button></button>').button({icon:'ui-icon-closethick'})
                          .addClass('cmd-del-ucast')
                          .prop('title','Odebrat účastníka')
                          .click(function(e){
                              e.preventDefault();
                              e.stopPropagation();
                              $(e.delegateTarget).button("disable");
                              //delUcast(data.record.tid);
                          });
                      return $('<span />').append($btnu);
                  }
              }
          },
          toolbar:{items:[{icon:"ui-icon-refresh",uiIcon:true,text:"Obnovit",click:function(e){$("#ucastTableContainer").jtable('reload');}},
              {icon:"ui-icon-plusthick",uiIcon:true,text:"Přidat účastníka",click:function(e){$("#add_date").dialog("open");}}]}
      });

      checkIsAdmin();
      setUcastnik(uname);
      $("#change_uname").dialog({autoOpen:false,title:"Jméno účastníka",modal:true,
          buttons:[{text:"Uložit",click:function(e){e.preventDefault();e.stopPropagation();saveName();$(this).dialog("close");}}]});
      $("#add_date").dialog({autoOpen:false,title:"Nový termín",modal:true,
          buttons:[{text:"Přidat",click:function(e){e.preventDefault();e.stopPropagation();addDate();$(this).dialog("close");}}]});
      $("#edit_date").datepicker({minDate:'today',dateFormat:'D dd. MM yy',altField:'#hide_date',altFormat:'yy-mm-dd'});
      $("#edit_ucast").dialog({autoOpen:false,title:"Přehled účastníků",modal:true,
          buttons:[{text:"OK",click:function(e){e.preventDefault();e.stopPropagation();/*addDate();*/$(this).dialog("close");}}]});
      if (!uname) $("#change_uname").dialog("open");
      setTimeout(refreshTable, 600000);
  });
//-->
</script>  
  </head>
  <body>
<div id="change_uname"><input type="text" class="jtable-input jtable-text-input" id="edit_uname" placeholder="jméno účastníka" title="Zde vložte své jméno" /></div>
<div id="add_date"><input type="text" class="jtable-input jtable-text-input" id="edit_date" placeholder="zadejte termín" title="Zde zadejte nový termín" /><input type="hidden" id="hide_date" /></div>
<div id="edit_ucast"><div id="ucastTableContainer"></div></div>
<div id="terminTableContainer"></div>
  </body>
</html>
