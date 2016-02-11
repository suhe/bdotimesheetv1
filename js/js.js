var agt=navigator.userAgent.toLowerCase(); 
var is_major = parseInt(navigator.appVersion); 
var is_minor = parseFloat(navigator.appVersion); 
var is_nav  = ((agt.indexOf('mozilla')!=-1) && (agt.indexOf('spoofer')==-1) 
            && (agt.indexOf('compatible') == -1) && (agt.indexOf('opera')==-1) 
            && (agt.indexOf('webtv')==-1)); 
var is_ie   = (agt.indexOf("msie") != -1); 


// Function Declare Variable
function Redirect(sUrl){
	window.location= sUrl; 
}

function addLoadEvent(func) {
    var oldonload = window.onload;
    if (typeof window.onload != 'function') {
        window.onload = func;
    } else {
        window.onload = function() {
            oldonload();
            func();
        }
    }
}

function trim(cString){
   return cString.replace(/^\s*|\s*$/g,"");
}


function RefreshParent() {
	window.opener.location.reload(true);
	if (window.opener.progressWindow) {
		window.opener.progressWindow.close()
	}
	window.close();
}


var printPageAuto = true; // Flag for whether or not to automatically call the print function

function printPage()

{	if (document.getElementById != null)
	{
		var html = '<HTML>\n<HEAD>\n';
		if (document.getElementsByTagName != null)
		{
			var headTags = document.getElementsByTagName("head");
			if (headTags.length > 0)
				html += headTags[0].innerHTML;
		}
		html += '\n</HE' + 'AD>\n<BODY>\n';
		var printReadyElem = document.getElementById("Printable");
		if (printReadyElem != null)
		{
				html += printReadyElem.innerHTML;
		}
		else
		{
			alert("Could not find the printReady section in the HTML");
			return;
		}
		html += '\n</BO' + 'DY>\n</HT' + 'ML>';
		var printWin = window.open("","printPage","width=0,height=0,top=0,left=0,resizable=yes,scrollbars=yes" );
		printWin.document.open();
		printWin.document.write(html);
		printWin.document.close();
		printWin.print();
		//printWin.close();
	}
	else
	{
		alert("Sorry, the print ready feature is only available in modern browsers.");
	}
}


function printPage1( intOLEcmd, intOLEparam )
{ // Create OLE Object
var WebBrowser = '<OBJECT ID="WebBrowser1" WIDTH=0 HEIGHT=0 CLASSID="CLSID:8856F961-340A-11D0-A96B-00C04FD705A2"></OBJECT>';

         // Place Object on page
         document.body.insertAdjacentHTML('beforeEnd', WebBrowser);

// if intOLEparam is not defined, set it
if ( ( ! intOLEparam ) || ( intOLEparam < -1 ) || ( intOLEparam > 1) )
intOLEparam = 1;



         // Execute Object
         WebBrowser1.ExecWB( intOLEcmd, intOLEparam );


         // Destroy Object
         WebBrowser1.outerHTML = "";
}


//------------------------------------------------------------------------------------------------
//	Buat PopUp Generator
//------------------------------------------------------------------------------------------------
function PopUp(url,  xWidth, xHeight) {
	var size = "yes";
	var cWidth = "450";
	var cHeight = "450";

	if (xWidth != null)	{ cWidth = xWidth	};
	if (xHeight != null)	{ cHeight = xHeight	};

	PosLeft = (screen.availWidth - cWidth) /2;
	PosTop = (screen.availHeight - cHeight) /2;	
	
	if(screen.width <= cWidth || screen.height <= cHeight) size = "yes";
	
	attribute = "width="+cWidth+",height="+cHeight+",top="+PosTop+",left="+PosLeft+",resizable=" + size + ",scrollbars=" + size + ",titlebar=yes,menubar=no,toolbar=no,status=yes", false

	if (typeof(smallwindow) != "object")
		smallwindow = window.open(url, 'win', attribute);
	else {
		if (!smallwindow.closed)
			smallwindow.location.href = url;
		else
			smallwindow = window.open(url, 'win', attribute);
		}
	smallwindow.focus();
}



//------------------------------------------------------------------------------------------------
//	Image Handling 
//------------------------------------------------------------------------------------------------
function ImageState(n,y) 	{
	if (document.getElementById) {
			var button = document.getElementById(n);
			button.style.backgroundPosition="0px "+y+"px";
	}
}
	
function ImageStateBlur(n){
	if (navigator.userAgent.indexOf("MSIE")!=-1) {
		alert(n);
		n.blur();
	}
}


//------------------------------------------------------------------------------------------------
//	Centering Element
//------------------------------------------------------------------------------------------------
function getWindowHeight() {
	var windowHeight = 0;
	if (typeof(window.innerHeight) == 'number') {
		windowHeight = window.innerHeight;
	}
	else {
		if (document.documentElement && document.documentElement.clientHeight) {
			windowHeight = document.documentElement.clientHeight;
		}
		else {
			if (document.body && document.body.clientHeight) {
				windowHeight = document.body.clientHeight;
			}
		}
	}
	return windowHeight;
}


function getWindowWidth() {
	var WindowWidth = 0;
	if (typeof(window.innerWidth) == 'number') {
		windowWidth = window.innerWidth;
	}
	else {
		if (document.documentElement && document.documentElement.clientWidth) {
			windowWidth = document.documentElement.clientWidth;
		}
		else {
			if (document.body && document.body.clientWidth) {
				windowWidth = document.body.clientWidth;
			}
		}
	}
	return windowWidth;
}

function setMessage() {
	if (document.getElementById) {
		var windowWidth  = getWindowWidth();
		var windowHeight = getWindowHeight();
		
		if (windowHeight > 0) {
			var contentElement = document.getElementById('message');
			var contentHeight = contentElement.offsetHeight;
			var contentWidth = contentElement.offsetWidth;
			if (windowHeight - contentHeight > 0) {
				contentElement.style.position = 'absolute';
				contentElement.style.top = ((windowHeight / 2) - (contentHeight / 2)) + 'px';
				contentElement.style.left = ((windowWidth / 2) - (contentWidth/ 2)) + 'px';
			}
			else {
				contentElement.style.position = 'static';
			}
		}
	}
}


//------------------------------------------------------------------------------------------------
//	KeyBoard Handling 
//------------------------------------------------------------------------------------------------
function nextKey(ev){
	if (is_nav) {
		var oKey = ev.which;
	} else {
		var oKey = ev.keyCode;
	}
	if (oKey==13) {
		if (is_nav) {
			event.which=9;
		} else {
			window.event.keyCode=9;
		}
	}
}


//------------------------------------------------------------------------------------------------
//	Key Mask Handling 
//------------------------------------------------------------------------------------------------
function Mask(field, event, sMask) {
  //var sMask = "**?##?####";

  var KeyTyped = String.fromCharCode(getKeyCode(event));
  var targ = getTarget(event);
  keyCount = targ.value.length;

	if(keyCount == sMask.length) 	{
		return false;
	}

  if ((sMask.charAt(keyCount+1) != '#') && (sMask.charAt(keyCount+1) != 'A' ) )   {
     	field.value = field.value + KeyTyped + sMask.charAt(keyCount+1);
     	return false;
  }

  if (sMask.charAt(keyCount) == '*')
			return true;

  if (sMask.charAt(keyCount) == KeyTyped) {
			return true;
  }

  if ((sMask.charAt(keyCount) == '#') && isNumeric(KeyTyped))
			return true;

  if ((sMask.charAt(keyCount) == 'A') && isAlpha(KeyTyped))
   		return true;

  if ((sMask.charAt(keyCount+1) == '?') )   {
     	field.value = field.value + KeyTyped + sMask.charAt(keyCount+1);
     	return true;
  }
	if (KeyTyped.charCodeAt(0) < 32) return true;
			return false;
}

function getTarget(e) {
// IE5
 if (e.srcElement) {
      return e.srcElement;
 }
  if (e.target) {
      return e.target;
 }
}

function getKeyCode(e) {
	//IE5
	if (e.srcElement) {
	      return e.keyCode
	}
	// NC5
	if (e.target) {
	 return e.which
	}
}

function isNumeric(c) {
  var sNumbers = "01234567890";
  if (sNumbers.indexOf(c) == -1)
    return false;
  else return true;
}

function isAlpha(c) {
  var lCode = c.charCodeAt(0);
  if (lCode >= 65 && lCode <= 122 ) {
  	return true;
  }
  else
  return false;
}

function isPunct(c) {
  var lCode = c.charCodeAt(0);
  if (lCode >= 32 && lCode <= 47 ) {
		return true;
  }
  else
  return false;
}




//------------------------------------------------------------------------------------------------
//	Buat Key Driven ComboBox
//------------------------------------------------------------------------------------------------
var timerid     = null;
var matchString = "";
var mseconds    = 1000;	// Length of time before search string is reset

function ComboKeySelected(keyCode,targ)
{
	keyVal      = String.fromCharCode(keyCode); 
	matchString = matchString + keyVal; 
	elementCnt  = targ.length - 1;	

	for (i = elementCnt; i > 0; i--)
	{
		selectText = targ.options[i].text.toLowerCase(); 
		if (selectText.substr(0,matchString.length) == 	matchString.toLowerCase())
		{
			targ.options[i].selected = true;
		}
	}

	clearTimeout(timerid); 
	timerid = setTimeout('matchString = ""',mseconds); 
	
	return false; 
}

function validateText(xThis,cLength){ 
	if (xThis.value.length > cLength){	
		alert('Remarks : '+xThis.value.length+' Character,harus ' + cLength + ' Character');
		xThis.focus();
		return false;
	} else {
		return true;
	}
} 


//------------------------------------------------------------------------------------------------
//	ValidateNumber
//------------------------------------------------------------------------------------------------
function validateNumber(xThis) {
	var el = event.srcElement;
	var sName = el.name;	
   var num = "0123456789.-";
   var sMessage ="";
   event.returnValue = true;

	for (var intLoop = 0; 
   	intLoop < el.value.length; intLoop++)
      	if (-1 == num.indexOf(el.value.charAt(intLoop))) 
         	event.returnValue=false;
			if (!event.returnValue){
				if (el.value=="") {
					sMessage = "Harus di ISI...!! \n";
				} else {	
					sMessage = "Harus di ISI dengan ANGKA...!! \n";
				}

				//message(sMessage);
    			alert(sMessage);
         	el.className = "TextFocus";
         	xThis.focus();
			} else {
	        el.className = "Text";
			}
}

var separator = ",";  // use comma as 000's separator
var decpoint = ".";  // use period as decimal point
var percent = "%";
var currency = "$";  // use dollar sign for currency

function formatNumber(number, format){

	if (number - 0 != number) return null;  
	var useSeparator = format.indexOf(separator) != -1;
	var usePercent = format.indexOf(percent) != -1;  
	var useCurrency = format.indexOf(currency) != -1;
	var isNegative = (number < 0);
	number = Math.abs (number);
	if (usePercent) number *= 100;
	format = strip(format, separator + percent + currency);
	number = "" + number;

	var dec = number.indexOf(decpoint) != -1;
	var nleftEnd = (dec) ? number.substring(0, number.indexOf(".")) : number;
	var nrightEnd = (dec) ? number.substring(number.indexOf(".") + 1) : "";

	dec = format.indexOf(decpoint) != -1;
	var sleftEnd = (dec) ? format.substring(0, format.indexOf(".")) : format;
	var srightEnd = (dec) ? format.substring(format.indexOf(".") + 1) : "";

	if (srightEnd.length < nrightEnd.length) {
	  var nextChar = nrightEnd.charAt(srightEnd.length) - 0;
	  nrightEnd = nrightEnd.substring(0, srightEnd.length);
	  if (nextChar >= 5) nrightEnd = "" + ((nrightEnd - 0) + 1);  

	  while (srightEnd.length > nrightEnd.length) {
		nrightEnd = "0" + nrightEnd;
	  }

	  if (srightEnd.length < nrightEnd.length) {
		nrightEnd = nrightEnd.substring(1);
		nleftEnd = (nleftEnd - 0) + 1;
	  }
	} else {
	  for (var i=nrightEnd.length; srightEnd.length > nrightEnd.length; i++) {
		if (srightEnd.charAt(i) == "0") nrightEnd += "0";  
		else break;
	  }
	}

	sleftEnd = strip(sleftEnd, "#");  
	while (sleftEnd.length > nleftEnd.length) {
	  nleftEnd = "0" + nleftEnd;  
	}

	if (useSeparator) nleftEnd = separate(nleftEnd, separator);  
	var output = nleftEnd + ((nrightEnd != "") ? "." + nrightEnd : "");  
	output = ((useCurrency) ? currency : "") + output + ((usePercent) ? percent : "");
	if (isNegative) {
	  output = (useCurrency) ? "(" + output + ")" : "-" + output;
	}
	return output;
}

function strip(input, chars) {
	var output = "";  
	for (var i=0; i < input.length; i++)
	  if (chars.indexOf(input.charAt(i)) == -1)
		output += input.charAt(i);
	return output;
}

function separate(input, separator) {  
	input = "" + input;
	var output = "";  
	for (var i=0; i < input.length; i++) {
	  if (i != 0 && (input.length - i) % 3 == 0) output += separator;
	  output += input.charAt(i);
	}
	return output;
}


//------------------------------------------------------------------------------------------------
//	ValidateTime
//------------------------------------------------------------------------------------------------

function validateTime(objName) {
	var timefield = objName;
	if (checkTime(objName) == false) {
		timefield.select();
		alert("PENGISIAN JAM TIDAK VALID ! format jam : [HH:MM:SS] ; Hour = 00-23 ; Minute = 00-59 ; Second = 00-59");
		timefield.focus();
		return false;
	}
	else {
		return true;
	}
}

function checkTime(objName) {
	var strTimestyle = "HH:MM";
	var strTime;
	var strTimeArray;
	var strHour;
	var strMinute;
	var strSecond;
	var inthour;
	var intminute;
	var intsecond;
	var booFound = false;
	var timefield = objName;
	var strSeparatorArray = new Array(":");
	var intElementNr;
	var err = 0;
	strTime = timefield.value; 
	
	if (strTime.length < 1) 
	{
		return true;
	}
	
	if (strTime.length < 3) 
	{
		return false;
	}
	
	for (intElementNr = 0; intElementNr < strSeparatorArray.length; intElementNr++) 
	{
		if (strTime.indexOf(strSeparatorArray[intElementNr]) != -1) 
		{
			strTimeArray = strTime.split(strSeparatorArray[intElementNr]);
			if (strTimeArray.length != 3) 
			{
				err = 1;
				return false;
			}
			else 
			{
				strHour = strTimeArray[0];
				strMinute = strTimeArray[1];
				strSecond = strTimeArray[2];
			}
			booFound = true;
   		}
	}
	if (booFound == false) {
		if (strTime.length>3) {
			strHour = strTime.substr(0, 2);
			strMinute = strTime.substr(2, 2);
			strSecond = strTime.substr(4, 2);
	   }
	}
	inthour = parseInt(strHour, 10);
	intminute = parseInt(strMinute, 10);
	intsecond = parseInt(strSecond, 10);
	
	if (inthour>23 || inthour<0) {
		err = 5;
		return false;
	}
	
	if (intminute>59 || intminute<0) {
		err = 5;
		return false;
	}

	if (intsecond>59 || intsecond<0) {
		err = 5;
		return false;
	}

	timefield.value = strHour + ":" + strMinute + ":" + strSecond
	return true;
}


//------------------------------------------------------------------------------------------------
//	ValidateDate 
//------------------------------------------------------------------------------------------------

var isNav4 = false, isNav5 = false, isIE4 = false
var strSeperator = "/"; 
var vDateType = 3; // Global value for type of date format
//                1 = mm/dd/yyyy
//                2 = yyyy/dd/mm  (Unable to do date check at this time)
//                3 = dd/mm/yyyy

var vYearType = 4; //Set to 2 or 4 for number of digits in the year for Netscape
var vYearLength = 2; // Set to 4 if you want to force the user to enter 4 digits for the year before validating.
var err = 0; // Set the error code to a default of zero
if(navigator.appName == "Netscape") {
	if (navigator.appVersion < "5") {
		isNav4 = true;
	isNav5 = false;
} else
	if (navigator.appVersion > "4") {
	isNav4 = false;
	isNav5 = true;
   }
}
	else {
	isIE4 = true;
}


function DateFormat(vDateName, vDateValue, e, dateCheck, dateType) {
vDateType = dateType;
vDateName.className = "Text";

	if (vDateValue == "~") {
		alert("AppVersion = "+navigator.appVersion+" \nNav. 4 Version = "+isNav4+" \nNav. 5 Version = "+isNav5+" \nIE Version = "+isIE4+" \nYear Type = "+vYearType+" \nDate Type = "+vDateType+" \nSeparator = "+strSeperator);
		vDateName.value = "";
		vDateName.focus();
		return true;
	}

var whichCode = (window.Event) ? e.which : e.keyCode;
// Check to see if a seperator is already present.bypass the date if a seperator is present and the length greater than 8
	if (vDateValue.length > 8 && isNav4) {
		if ((vDateValue.indexOf("-") >= 1) || (vDateValue.indexOf("/") >= 1))
		return true;
	}

//Eliminate all the ASCII codes that are not valid
var alphaCheck = " abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ/-";
	if (alphaCheck.indexOf(vDateValue) >= 1) {
	if (isNav4) {
		vDateName.value = "";
		vDateName.focus();
		vDateName.select();
		return false;
	}else {
		vDateName.value = vDateName.value.substr(0, (vDateValue.length-1));
		return false;
	   }
	}

if (whichCode == 8) 
return false;
else {
var strCheck = '47,48,49,50,51,52,53,54,55,56,57,58,59,95,96,97,98,99,100,101,102,103,104,105';
if (strCheck.indexOf(whichCode) != -1) {
if (isNav4) {
	if (((vDateValue.length < 6 && dateCheck) || (vDateValue.length == 7 && dateCheck)) && (vDateValue.length >=1)) {
		alert("Mohon di tulis dengan ANGKA..\n Mohon DI tulis lagi..");

		vDateName.value = "";
		vDateName.focus();
		vDateName.select();
		vDateName.className = "TextFocus";
		return false;
	}
if (vDateValue.length == 6 && dateCheck) {
	var mDay = vDateName.value.substr(2,2);
	var mMonth = vDateName.value.substr(0,2);
	var mYear = vDateName.value.substr(4,4)
	if (mYear.length == 2 && vYearType == 4) {
		var mToday = new Date();
		var checkYear = mToday.getFullYear() + 30; 
		var mCheckYear = '20' + mYear;
		if (mCheckYear >= checkYear)
		mYear = '19' + mYear;
		else
		mYear = '20' + mYear;
	}

	var vDateValueCheck = mMonth+strSeperator+mDay+strSeperator+mYear;
	if (!dateValid(vDateValueCheck)) {
		alert("Invalid Date\nPlease Re-Enter");
		vDateName.value = "";
		vDateName.focus();
		vDateName.select();
		vDateName.className = "TextFocus";
		return false;
	}
	return true;
}
else {
if (vDateValue.length >= 8  && dateCheck) {
	if (vDateType == 1) // mmddyyyy
	{
		var mDay = vDateName.value.substr(2,2);
		var mMonth = vDateName.value.substr(0,2);
		var mYear = vDateName.value.substr(4,4)
		vDateName.value = mMonth+strSeperator+mDay+strSeperator+mYear;
	}
	if (vDateType == 2) // yyyymmdd
	{
	var mYear = vDateName.value.substr(0,4)
	var mMonth = vDateName.value.substr(4,2);
	var mDay = vDateName.value.substr(6,2);
	vDateName.value = mYear+strSeperator+mMonth+strSeperator+mDay;
	}
	if (vDateType == 3) // ddmmyyyy
	{
	var mMonth = vDateName.value.substr(2,2);
	var mDay = vDateName.value.substr(0,2);
	var mYear = vDateName.value.substr(4,4)
	vDateName.value = mDay+strSeperator+mMonth+strSeperator+mYear;
	}

	var vDateTypeTemp = vDateType;
	vDateType = 1;
	var vDateValueCheck = mMonth+strSeperator+mDay+strSeperator+mYear;

	if (!dateValid(vDateValueCheck)) {
		alert("Invalid Date\nPlease Re-Enter");
		vDateType = vDateTypeTemp;
		vDateName.value = "";
		vDateName.focus();
		vDateName.select();
		vDateName.className = "TextFocus";
		return false;
	}
	vDateType = vDateTypeTemp;
	return true;
}
else {
	if (((vDateValue.length < 8 && dateCheck) || (vDateValue.length == 9 && dateCheck)) && (vDateValue.length >=1)) {
				alert("Invalid Date\nPlease Re-Enter");
				vDateName.value = "";
				vDateName.focus();
				vDateName.select();
				vDateName.className = "TextFocus";
				return false;
	         }
	      }
	   }
}
else {
// Non isNav Check
if (((vDateValue.length < 8 && dateCheck) || (vDateValue.length == 9 && dateCheck)) && (vDateValue.length >=1)) {
	alert("Invalid Date\nPlease Re-Enter");
	vDateName.value = "";
	vDateName.focus();
	vDateName.className = "TextFocus";
	return true;
}
if (vDateValue.length >= 8 && dateCheck) {
	if (vDateType == 1) // mm/dd/yyyy
	{
		var mMonth = vDateName.value.substr(0,2);
		var mDay = vDateName.value.substr(3,2);
		var mYear = vDateName.value.substr(6,4)
	}
	if (vDateType == 2) // yyyy/mm/dd
	{
		var mYear = vDateName.value.substr(0,4)
		var mMonth = vDateName.value.substr(5,2);
		var mDay = vDateName.value.substr(8,2);
	}
	if (vDateType == 3) // dd/mm/yyyy
	{
		var mDay = vDateName.value.substr(0,2);
		var mMonth = vDateName.value.substr(3,2);
		var mYear = vDateName.value.substr(6,4)
	}
if (vYearLength == 4) {
	if (mYear.length < 4) {
		//message("Penulisan TAHUN, Maksimum 4 ANGKA\Mohon di Tulis Ulang");	
		alert("Penulisan TAHUN, Maksimum 4 ANGKA\Mohon di Tulis Ulang");
		//alert("Invalid Date\nPlease Re-Enter");
		vDateName.value = "";
		vDateName.focus();
		vDateName.className = "TextFocus";
		return true;
   }
}
var vDateTypeTemp = vDateType;
vDateType = 1;
var vDateValueCheck = mMonth+strSeperator+mDay+strSeperator+mYear;
if (mYear.length == 2 && vYearType == 4 && dateCheck) {
var mToday = new Date();
var checkYear = mToday.getFullYear() + 30; 
var mCheckYear = '20' + mYear;
if (mCheckYear >= checkYear)
mYear = '19' + mYear;
else
mYear = '20' + mYear;
vDateValueCheck = mMonth+strSeperator+mDay+strSeperator+mYear;
if (vDateTypeTemp == 1) // mm/dd/yyyy
vDateName.value = mMonth+strSeperator+mDay+strSeperator+mYear;
if (vDateTypeTemp == 3) // dd/mm/yyyy
vDateName.value = mDay+strSeperator+mMonth+strSeperator+mYear;
} 
if (!dateValid(vDateValueCheck)) {
alert("Invalid Date\nPlease Re-Enter");
vDateType = vDateTypeTemp;
vDateName.value = "";
vDateName.focus();
vDateName.className = "TextFocus";
return true;
}
vDateType = vDateTypeTemp;
return true;
}
else {
	if (vDateType == 1) {
		if (vDateValue.length == 2) {
				vDateName.value = vDateValue+strSeperator;
		}
		if (vDateValue.length == 5) {
				vDateName.value = vDateValue+strSeperator;
		}
	}
	if (vDateType == 2) {
		if (vDateValue.length == 4) {
				vDateName.value = vDateValue+strSeperator;
		}
		if (vDateValue.length == 7) {
				vDateName.value = vDateValue+strSeperator;
	   }
	} 
	if (vDateType == 3) {
		if (vDateValue.length == 2) {
				vDateName.value = vDateValue+strSeperator;
		}
		if (vDateValue.length == 5) {
				vDateName.value = vDateValue+strSeperator;
	   }
	}
	return true;
   }
}
if (vDateValue.length == 10&& dateCheck) {
if (!dateValid(vDateName)) {
// Un-comment the next line of code for debugging the dateValid() function error messages
//alert(err);  
alert("Invalid Date\nPlease Re-Enter");
vDateName.focus();
vDateName.select();
vDateName.className = "TextFocus";
   }
}
return false;
}
else {
if (isNav4) {
	vDateName.value = "";
	vDateName.focus();
	vDateName.select();
	return false;
}else{
	vDateName.value = vDateName.value.substr(0, (vDateValue.length-1));
	return false;
         }
      }
   }
}
function dateValid(objName) {
var strDate;
var strDateArray;
var strDay;
var strMonth;
var strYear;
var intday;
var intMonth;
var intYear;
var booFound = false;
var datefield = objName;
var strSeparatorArray = new Array("-"," ","/",".");
var intElementNr;
// var err = 0;
var strMonthArray = new Array(12);
strMonthArray[0] = "Jan";
strMonthArray[1] = "Feb";
strMonthArray[2] = "Mar";
strMonthArray[3] = "Apr";
strMonthArray[4] = "May";
strMonthArray[5] = "Jun";
strMonthArray[6] = "Jul";
strMonthArray[7] = "Aug";
strMonthArray[8] = "Sep";
strMonthArray[9] = "Oct";
strMonthArray[10] = "Nov";
strMonthArray[11] = "Dec";
//strDate = datefield.value;
strDate = objName;
if (strDate.length < 1) {
return true;
}
for (intElementNr = 0; intElementNr < strSeparatorArray.length; intElementNr++) {
if (strDate.indexOf(strSeparatorArray[intElementNr]) != -1) {
	strDateArray = strDate.split(strSeparatorArray[intElementNr]);
	if (strDateArray.length != 3) {
		err = 1;
		return false;
	}
	else {
		strDay = strDateArray[0];
		strMonth = strDateArray[1];
		strYear = strDateArray[2];
	}
	booFound = true;
   }
}
if (booFound == false) {
if (strDate.length>5) {
		strDay = strDate.substr(0, 2);
		strMonth = strDate.substr(2, 2);
		strYear = strDate.substr(4);
   }
}

if (strYear.length == 2) {
strYear = '20' + strYear;
}
strTemp = strDay;
strDay = strMonth;
strMonth = strTemp;
intday = parseInt(strDay, 10);
if (isNaN(intday)) {
err = 2;
return false;
}
intMonth = parseInt(strMonth, 10);
if (isNaN(intMonth)) {
for (i = 0;i<12;i++) {
if (strMonth.toUpperCase() == strMonthArray[i].toUpperCase()) {
intMonth = i+1;
strMonth = strMonthArray[i];
i = 12;
   }
}
if (isNaN(intMonth)) {
err = 3;
return false;
   }
}
intYear = parseInt(strYear, 10);
if (isNaN(intYear)) {
err = 4;
return false;
}
if (intMonth>12 || intMonth<1) {
err = 5;
return false;
}
if ((intMonth == 1 || intMonth == 3 || intMonth == 5 || intMonth == 7 || intMonth == 8 || intMonth == 10 || intMonth == 12) && (intday > 31 || intday < 1)) {
err = 6;
return false;
}
if ((intMonth == 4 || intMonth == 6 || intMonth == 9 || intMonth == 11) && (intday > 30 || intday < 1)) {
err = 7;
return false;
}
if (intMonth == 2) {
if (intday < 1) {
err = 8;
return false;
}
if (LeapYear(intYear) == true) {
if (intday > 29) {
err = 9;
return false;
   }
}
else {
if (intday > 28) {
err = 10;
return false;
      }
   }
}
return true;
}
function LeapYear(intYear) {
if (intYear % 100 == 0) {
if (intYear % 400 == 0) { return true; }
}
else {
if ((intYear % 4) == 0) { return true; }
}
return false;
}
