/*
===================================================================
Copyright DHTMLX LTD. http://www.dhtmlx.com
This code is obfuscated and not allowed for any purposes except 
using on sites which belongs to DHTMLX LTD.

Please contact sales@dhtmlx.com to obtain necessary 
license for usage of dhtmlx components.
===================================================================
*/function eXcell_link(cell){this.cell=cell;this.grid=this.cell.parentNode.grid;this.isDisabled=function(){return true;};this.edit=function(){};this.getValue=function(){if(this.cell.firstChild.getAttribute)return this.cell.firstChild.innerHTML+"^"+this.cell.firstChild.getAttribute("href");else return "";};this.setValue=function(val){if((typeof(val)!="number")&&(!val||val.toString().PA()=="")){this.dq("&nbsp;",qg);return(this.cell.mG=true);};var qg=val.split("^");if(qg.length==1)qg[1]="";else{if(qg.length>1){qg[1]="href='"+qg[1]+"'";if(qg.length==3)qg[1]+=" target='"+qg[2]+"'";else qg[1]+=" target='_blank'";}};this.dq("<a "+qg[1]+" onclick='(_isIE?event:arguments[0]).cancelBubble = true;'>"+qg[0]+"</a>",qg);}};eXcell_link.prototype=new gD;eXcell_link.prototype.getTitle=function(){var z=this.cell.firstChild;return((z&&z.tagName)?z.getAttribute("href"):"");};eXcell_link.prototype.getContent=function(){var z=this.cell.firstChild;return((z&&z.tagName)?z.innerHTML:"");};