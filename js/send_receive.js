function request_voting(symbolName) {
    jQuery.ajax({
        type: "POST",
        url: ajax_unique.ajaxurl,
        data: {
            action: "request_votingfromServer",
            title: ajax_unique.title,
            symbolName: symbolName
        },
        success: function(data, textStatus, XMLHttpRequest) {
            updateVotingValues(data);
            return data;
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            alert("Data couldn't load.");
        }
    });
}
function send_vote(element, symbolName, voting_number) {
    jQuery.ajax({
        type: "POST",
        url: ajax_unique.ajaxurl,
        dataType: "json",
        data: {
            action: "send_votingToServer",
            title: ajax_unique.title,
            symbolName: symbolName,
            voting_number: voting_number
        },
        success: function(data, textStatus, XMLHttpRequest) {
            request_voting(symbolName);
            changeVotingButtonAfterSend(element);
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            alert("Sending didn't work. Try again.");
        }
    });
}
