<%@ page import="java.io.*" %>

<%@ page import="java.util.Collection" %>
<%@ page import="javax.servlet.http.Part" %>

<%@ page import="org.apache.commons.io.FilenameUtils" %>
<%@ page import="java.io.File" %>
<%@ page import="java.io.IOException" %>
<%@ page import="java.util.Iterator" %>
<%@ page import="java.util.List" %>
 
<%@ page import="javax.servlet.ServletException" %>
<%@ page import="javax.servlet.http.HttpServlet" %>
<%@ page import="javax.servlet.http.HttpServletRequest" %>
<%@ page import="javax.servlet.http.HttpServletResponse" %>
 
<%@ page import="org.apache.commons.fileupload.FileItem" %>
<%@ page import="org.apache.commons.fileupload.FileUploadException" %>
<%@ page import="org.apache.commons.fileupload.disk.DiskFileItemFactory" %>
<%@ page import="org.apache.commons.fileupload.servlet.ServletFileUpload" %>

<%

/*

THE FOLLOW FILES ARE REQUIRED IN YOUR PROJECT:
commons-fileupload.jar
commons-io.jar



HTML5/FLASH MODE

(MODE will detected on client side automaticaly. Working mode will passed to server as GET param "mode")

response format

if upload was good, you need to specify state=true and name - will passed in form.send() as serverName param
{state: 'true', name: 'filename'}

HTML4 MODE

response format:

to cancel uploading
{state: 'cancelled'}

if upload was good, you need to specify state=true, name - will passed in form.send() as serverName param, size - filesize to update in list
{state: 'true', name: 'filename', size: 1234}

*/

try {
	ServletFileUpload uploader = new ServletFileUpload(new DiskFileItemFactory());
	List<FileItem> items = uploader.parseRequest(request);

	// Detecting mode and action
	String mode = request.getParameter("mode");
	String action = "";
	if (mode == null) {
		for (FileItem item : items) {
			
			if (item.getFieldName().equals("mode")) {
				InputStream is = item.getInputStream();
				BufferedReader br = new BufferedReader(new InputStreamReader(is));
				StringBuilder sb = new StringBuilder();
		    	String line;
				while ((line = br.readLine()) != null) {
					sb.append(line);
		    	}
				mode = sb.toString();
			}

			if (item.getFieldName().equals("action")) {
				InputStream is = item.getInputStream();
				BufferedReader br = new BufferedReader(new InputStreamReader(is));
				StringBuilder sb = new StringBuilder();
		    	String line;
				while ((line = br.readLine()) != null) {
					sb.append(line);
		    	}
				action = sb.toString();
			}
		}
	}

	// cancel uploading or upload file according action
	if (action.equals("cancel"))
		out.print("{state:'cancelled'}");
	else {
		String filename = "";
		Integer filesize = 0;
		for (FileItem item : items) {
			if (!item.isFormField()) {
				// Process form file field (input type="file").
				String fieldname = item.getFieldName();
				filename = FilenameUtils.getName(item.getName());
				InputStream filecontent = item.getInputStream();

				// Write to file
//				File f=new File(filename);
//				OutputStream fout=new FileOutputStream(f);
//				byte buf[]=new byte[1024];
//				int len;
//				while((len=filecontent.read(buf))>0) {
//					fout.write(buf,0,len);
//					filesize+=len;
//				}
//				fout.close();
			}
		}
		out.print("{state: true, name:'" + filename.replace("'","\\'") + "', size: " + filesize + "}");
	}
} catch (FileUploadException e) {
	throw new ServletException("Cannot parse multipart request.", e);
}

%>