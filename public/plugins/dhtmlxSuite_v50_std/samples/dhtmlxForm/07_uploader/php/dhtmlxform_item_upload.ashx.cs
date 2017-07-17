using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;

namespace dhtmlxForm
{
    /// <summary>
    /// Summary description for dhtmlxform_item_upload
    /// </summary>
    public class dhtmlxform_item_upload : IHttpHandler
    {

        public void ProcessRequest(HttpContext context)
        {
            /*
            HTML5/FLASH MODE
            (MODE will detected on client side automaticaly. Working mode will passed to server as GET param "mode")
            response format
            if upload was good, you need to specify state=true and name - will passed in form.send() as serverName param
            {state: 'true', name: 'filename'}
            */
            string filename = "";
            
            if (context.Request["mode"] == "html5" || context.Request["mode"] == "flash") {               
	            filename = context.Request.Files["file"].FileName;
	            context.Response.ContentType = "text/json";
	            context.Response.Write("{state: true, name:'" + filename.Replace("'","\\'")+"'}");
            }

            /*
            HTML4 MODE
            response format:
            to cancel uploading
            {state: 'cancelled'}
            if upload was good, you need to specify state=true, name - will passed in form.send() as serverName param, size - filesize to update in list
            {state: 'true', name: 'filename', size: 1234}
            */

            if (context.Request["mode"] == "html4") {
            	    context.Response.ContentType = "text/html";
	            if (context.Request["action"] == "cancel") {
		            context.Response.Write("{state:'cancelled'}");
	            } else {
		            filename = context.Request.Files["file"].FileName;	   		         
		             context.Response.Write("{state: true, name:'" + filename.Replace("'","\\'")+"', size:" + context.Request.Files["file"].ContentLength+"}");
	            }
            }
            
        }

        public bool IsReusable
        {
            get
            {
                return false;
            }
        }
    }
}