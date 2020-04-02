document.addEventListener("DOMContentLoaded", theDomHasLoaded, false);
window.addEventListener("load", pageFullyLoaded, false);

var SymbolToStockName;
var VotingURL="https://stockvoting.net/voting";

function theDomHasLoaded(e) {
  if(window.location.href==VotingURL)
  {
    loadIncludes();
  }
}

// Will fire after theDomHasLoaded
function pageFullyLoaded(e) {
  if(window.location.href==VotingURL)
  {
    buildtemplates();
  }
}

function loadIncludes() {
  var script = document.createElement("script");

  script.src =
    "https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js";
  document.getElementsByTagName("head")[0].appendChild(script);
}


function buildtemplates() {
  getStockDict();
  // Move functionality from setSymboltoStockName
}

// is should be move of the build chain -> encapsulated
function getStockDict() {
  $(document).ready(function() {
    $.ajax({
        type: "GET",
        url: "https://stockvoting.net/wp-content/themes/twentytwenty/js/stockList.csv",
        dataType: "text",
        asnyc:false,
        success: function(data) {
          stockDict=SymbolToStockNameDict(data);
          setSymbolToStockName(stockDict);

          return stockDict;
        }
     });
  });
}

function setSymbolToStockName(stockDict) {
  const listofStockNames = Object.keys(stockDict);
  SymbolToStockName=stockDict;

  listofStockNames.forEach(function(item, index, array) {
    // Create the div section (see voting_template.html) for each stock
    create_inst_of_template(item);
    request_voting(item); // will trigger a request to update the value of the voting_input boxes
  });
}

function create_inst_of_template(symbolName) {
  // Get parentElement
  var parentElement = document.getElementById("section-inner");

  // Create a copy of the template for creating an instance
  var template_clone = document
    .getElementById("voting-template")
    .cloneNode(true);

    // Search and replace the template_company with the stockName
    stockName = SymbolToStockName[symbolName];

  template_clone.innerHTML = template_clone.innerHTML.replace(
    new RegExp("template_company_stockName", "g"),
    stockName
  );

  template_clone.innerHTML = template_clone.innerHTML.replace(
    new RegExp("template_company_symbol", "g"),
    symbolName
  );

  // Append the new template inst in DOM
  parentElement.appendChild(template_clone.content);

  var gauge_id = "#gaugeID_" + symbolName;
  //todo: optimize path
  // https://api.jquery.com/load/
  $(gauge_id).load(
    "https://stockvoting.net/wp-content/themes/twentytwenty/own-template-parts/gauge.html"
  );
}

function updateVotingValues(request_votingArray) {
  var request_votingArray_parsed = jQuery.parseJSON(request_votingArray);
  changeVotingValues(
    request_votingArray_parsed.symbolName,
    request_votingArray_parsed.voting_number,
    request_votingArray_parsed.actual_value
  );
  changePrognosis(
    request_votingArray_parsed.symbolName,
    request_votingArray_parsed.voting_number
  );
}

// is callback, triggered from the request_voting
// request_voting(js) => request_votingfromServer (php) => MySQL
// MySQL => (return) request_votingfromServer(php) => (echo) request_voting(js)
// request_voting => changeVotingValues()
function changeVotingValues(symbolName, voting_number, actual_value) {
  if (voting_number == "") {
    voting_number = 0;
  }
  var votingInputIDStr = "voting_input_" + symbolName;
  var votingInputElement = document.getElementById(votingInputIDStr);

  // Set the voting number, received from the server

  votingInputElement.value = Math.round(actual_value);
  changeGauge(symbolName, voting_number, actual_value);
}

function changeGauge(symbolName, voting_number, actual_value) {
  var gauge_id = "gaugeID_" + symbolName;
  var gaugeElement = document.getElementById(gauge_id);

  // Setting the voting_number
  gaugeElement.children[0].dataset.value = getPercentage(
    actual_value,
    voting_number
  );

  // Set the voting number, received from the server
  gaugeElement.value = actual_value;
}

function changePrognosis(symbolName, voting_number) {
  var elementPrognosis = document.getElementById("prognosis_" + symbolName);

  elementPrognosis.innerHTML = voting_number + " $";
}

/*********************onLoad/onClick Functions******************/

/*********************Helper******************/
function getPercentage(actual_value, voting_number) {
  return (voting_number / actual_value - 1) * 100;
}




/*********************Style functions******************/

function changeVotingButtonAfterSend(element) {
     // Prohibit double voting
     element.disabled = true;
     // Change text to alternative value
     element.value = element.alt;
     // Change background to default
     element.style.background="#1d4e9e";
  }
  
// To ReadFile Function
function SymbolToStockNameDict(allText) {
  var endLineSeparation='\n';
  var splitLineSeparation=',';
  var allTextLines = allText.split(endLineSeparation);
  var headers = allTextLines[0].split(splitLineSeparation);

  // Iterate through all lines
  dict={};

  for (var i=1; i<allTextLines.length; i++) {
    
      var data = allTextLines[i].split(splitLineSeparation);
      // Check if header matches the data
      if (data.length == headers.length) {
        // Key is TSLA, value is Tesla 
        dict[data[0]]=data[1];
        }
      }
  return dict;
}