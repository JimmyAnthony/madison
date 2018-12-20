
String.prototype.strReverse = function() {
	var newstring = "";
	for (var s=0; s < this.length; s++) {
		newstring = this.charAt(s) + newstring;
	}
	return newstring;
};

function chkPass(pwd) {
	var oScorebar = $("#scorebar");
	var oScore = $("#score");
	// var oComplexity = $("#complexity");
	var nScore=0, nLength=0, nAlphaUC=0, nAlphaLC=0, nNumber=0, nSymbol=0, nMidChar=0, nRequirements=0, nUnqChar=0, nSeqAlpha=0, nSeqNumber=0, nSeqSymbol=0;
	var nMultSeqAlpha=3, nMultSeqNumber=3, nMultSeqSymbol=3;
	var nMultLength=4, nMultNumber=4;
	var nMultSymbol=6;
	var sSymbol="0";
	var sAlphas = "abcdefghijklmnopqrstuvwxyz";
	var sNumerics = "01234567890";
	var sSymbols = ")!@#$%^&*()";

	var sComplexity = "Too Short";
	
	if (document.all) { var nd = 0; } else { var nd = 1; }
	if (pwd) {
		nScore = parseInt(pwd.length * nMultLength);
		nLength = pwd.length;
		var arrPwd = pwd.replace(/\s+/g,"").split(/\s*/);
		var arrPwdLen = arrPwd.length;
		
		for (var a=0; a < arrPwdLen; a++) {
			if (arrPwd[a].match(/[A-Z]/g)) {
				nAlphaUC++;
			}
			else if (arrPwd[a].match(/[a-z]/g)) { 
				nAlphaLC++;
			}
			else if (arrPwd[a].match(/[0-9]/g)) { 
				if (a > 0 && a < (arrPwdLen - 1)) { nMidChar++; }
				nNumber++;
			}
			else if (arrPwd[a].match(/[^a-zA-Z0-9_]/g)) { 
				if (a > 0 && a < (arrPwdLen - 1)) { nMidChar++; }
				nSymbol++;
			}
		}
		
		for (var s=0; s < 23; s++) {
			var sFwd = sAlphas.substring(s,parseInt(s+3));
			var sRev = sFwd.strReverse();
			if (pwd.toLowerCase().indexOf(sFwd) != -1 || pwd.toLowerCase().indexOf(sRev) != -1) { 
				nSeqAlpha++; 
			}
		}
		
		for (var s=0; s < 8; s++) {
			var sFwd = sNumerics.substring(s,parseInt(s+3));
			var sRev = sFwd.strReverse();
			if (pwd.toLowerCase().indexOf(sFwd) != -1 || pwd.toLowerCase().indexOf(sRev) != -1) { 
				nSeqNumber++; 
			}
		}
		
		for (var s=0; s < 8; s++) {
			var sFwd = sSymbols.substring(s,parseInt(s+3));
			var sRev = sFwd.strReverse();
			if (pwd.toLowerCase().indexOf(sFwd) != -1 || pwd.toLowerCase().indexOf(sRev) != -1) { 
				nSeqSymbol++; 
			}
		}

		if (nAlphaUC > 0 && nAlphaUC < nLength) {	
			nScore = parseInt(nScore + ((nLength - nAlphaUC) * 2));
		}
		if (nAlphaLC > 0 && nAlphaLC < nLength) {	
			nScore = parseInt(nScore + ((nLength - nAlphaLC) * 2)); 
		}
		if (nNumber > 0 && nNumber < nLength) {	
			nScore = parseInt(nScore + (nNumber * nMultNumber));
		}
		if (nSymbol > 0) {	
			nScore = parseInt(nScore + (nSymbol * nMultSymbol));
		}
		
		if ((nAlphaLC > 0 || nAlphaUC > 0) && nSymbol === 0 && nNumber === 0) {  
			nScore = parseInt(nScore - nLength);
		}
		if (nSeqAlpha > 0) {
			nScore = parseInt(nScore - (nSeqAlpha * nMultSeqAlpha)); 
		}
		if (nSeqNumber > 0) {
			nScore = parseInt(nScore - (nSeqNumber * nMultSeqNumber)); 
		}
		if (nSeqSymbol > 0) {
			nScore = parseInt(nScore - (nSeqSymbol * nMultSeqSymbol)); 
		}

		if (nScore > 100) { nScore = 100; } else if (nScore < 0) { nScore = 0; }
		if (nScore >= 0 && nScore < 20) { sComplexity = "Very Weak"; }
		else if (nScore >= 20 && nScore < 40) { sComplexity = "Weak"; }
		else if (nScore >= 40 && nScore < 60) { sComplexity = "Good"; }
		else if (nScore >= 60 && nScore < 80) { sComplexity = "Strong"; }
		else if (nScore >= 80 && nScore <= 100) { sComplexity = "Very Strong"; }
		
		oScorebar.css('background-position', "-" + parseInt(nScore * 4) + "px 0%")
		// oScorebar.style.backgroundPosition = "-" + parseInt(nScore * 4) + "px";
		oScore.html(nScore + "%");
	}
	else {
		initPwdChk();
		oScore.innerHTML = nScore + "%";
		// oComplexity.innerHTML = sComplexity;
	}
}

function initPwdChk(restart) {
	$("scorebar").css('background-position', "0");
}
