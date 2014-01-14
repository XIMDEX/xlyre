/**
 *  \details &copy; 2011  Open Ximdex Evolution SL [http://www.ximdex.org]
 *
 *  Ximdex a Semantic Content Management System (CMS)
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU Affero General Public License as published
 *  by the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU Affero General Public License for more details.
 *
 *  See the Affero GNU General Public License for more details.
 *  You should have received a copy of the Affero GNU General Public License
 *  version 3 along with Ximdex (see LICENSE file).
 *
 *  If not, visit http://gnu.org/licenses/agpl-3.0.html.
 *
 *  @author Ximdex DevTeam <dev@ximdex.com>
 *  @version $Revision$
 */


X.actionLoaded(function(event, fn, params) {

    var form = params.actionView.getForm('as_form');
    var fm = form.getFormMgr();
    var submit = fn('.validate').get(0);
	var name="";

	var url_params=fm.options.form.action.split("&");
	$.each(url_params, function( index, value ) {
		if(value.indexOf("name=")==0){
			name=value.substring(5,value.length);
		}
	});

	if(name!=""){
		fn("input#name").val(name);
	}

    fn('select#type_sec').change(function() {
        var type= fn('#type_sec option:selected').val();
        var urler = fn('#nodeURL').val() + '&type_sec=' + type;
        if(type!=3){
            urler=fn('#nodeURL').val().replace("createcatalog","addsectionnode") + '&type_sec=' + type;
        }   
		
        if(fn("input#name").val()!=""){
            urler+="&name="+fn("input#name").val();
        }
        fn('#as_form').attr('action', urler);
        fm.sendForm();
    }); 

	fn("a.add-dataset").click(function(){
		var $ds=$(".subfolder:last");
		var last_id=parseInt($(".subfolder > label").last().attr("data-cont"));
		last_id=last_id+1;
		var ident= fn("input[name='nodeid']").val()+'_dataset'+last_id;

        $infoSpan = $(document.createElement('span')).text("A dataset should be for a single data in several formats.");
        $infoSpan.addClass("info");

        $newElem = $(document.createElement('div'));
        $newElem.addClass("subfolder box-col1-1");

        $newLabel = $(document.createElement('label'));
        $newLabel.attr("for",ident);
        $newLabel.attr("data-cont",last_id);
        $newLabel.addClass("icon");

        $icon=$(document.createElement('strong')).addClass("icon dataset");

        $newInput = $(document.createElement('input'));
        $newInput.attr("id",ident);
        $newInput.attr("type","text");
        $newInput.attr("placeholder","New Dataset");
        $newInput.attr("name","datasets[]");
        $newInput.addClass("text_label");

        $deleteLink = $(document.createElement('a')).addClass("xim-tagsinput-tag-remove icon");
        $deleteLink.attr("href","#");
        $deleteLink.text("×").click(function(){
                                        $(this.parentNode.parentNode).remove();
                                   });

        $newLabel.append($newInput);
        $newLabel.append($icon);
        $newLabel.append($deleteLink);
        $newElem.append($newLabel);
        $newElem.append($infoSpan);

		$ds.before($newElem);
                    
    }); 

	$('a.xim-tagsinput-tag-remove').click(
	    function(event) {
            $(this.parentNode.parentNode).remove();
        });

    submit.beforeSubmit.add(function(event, button) {
        if(fn("input#name").val()==""){
            fn("input#name").addClass("validable");
            fn("input#name").addClass("not_empty");
        }
    });

});

