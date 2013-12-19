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

        fn('select#type_sec').change(function() {
                var type= fn('#type_sec option:selected').val();
                var urler = fn('#nodeURL').val() + '&type_sec=' + type;
                if(type==4){
                        urler=fn('#nodeURL').val().replace("addsectionnode","createcatalog") + '&type_sec=' + type + '&mod=xlyre';
                }   
                fn('#as_form').attr('action', urler);
                fm.sendForm();
                    
        }); 

        fn("a.add-dataset").click(function(){
		var $ds=$(".subfolder:last");
		var last_id=parseInt($(".subfolder > label").last().attr("data-cont"));
		last_id=last_id+1;
		var ident= fn("input[name='nodeid']").val()+'_dataset'+last_id;
		$ds.before('<div class="subfolder box-col1-1"><label for="'+ident+'" class="icon" data-cont="'+last_id+'"><input id="'+ident+'" type="text" class="text_label" name="namelst[]" placeholder="New Dataset"><strong class="icon dataset"></strong><a class="xim-tagsinput-tag-remove icon" href="#"> × </a></label><span class="info">A dataset should be for a single data in several formats.</span></div>');
                
        }); 

	fn('a.xim-tagsinput-tag-remove').click(
		function(event) {
        		//event.preventDefault();
			alert("borro!");

                });
});

