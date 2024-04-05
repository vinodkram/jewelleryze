<html>
<head><title>Sample Transaction File</title></head>
<body>
<%@ page import = "java.io.*, com.ccavenue.transaction.util.AesCryptUtil" %>
<%@include file="libFunctions.jsp"%>
<%
 String merchant_id = "2432439"; //Put your merchant id here
 String access_code = "AVIR02KH57BY38RIYB"; //Put access code here
 String enc_key = "3E1C06CA9EDA332A89C03F65D0619238"; //Put encryption key here
 Enumeration enumeration=request.getParameterNames ();
 String ccaRequest="", pname="", pvalue="";
 while (enumeration.hasMoreElements ()) {
 pname = ""+enumeration.nextElement ();
 pvalue = request.getParameter (pname);
 ccaRequest = ccaRequest + pname + "=" + pvalue + "&";
 }
 AesCryptUtil aesUtil=new AesCryptUtil (enc_key);
 String encRequest=aesUtil.encrypt (ccaRequest);
%>
<form method="post" name="redirect"
action="https://test.ccavenue.com/transaction/transaction.do? command= initiateTransaction"/>
<input type="hidden" id="encRequest" name="encRequest" value="<%= encRequest %>">
<input type="hidden" name="access_code" id="access_code" value="<%= access_code %>">
<script language='javascript'>document.redirect.submit ();</script>
</form>
</body>
</html>