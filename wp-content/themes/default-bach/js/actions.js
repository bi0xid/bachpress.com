function close_ticket(id)
{
document.location.href= "http://"+location.hostname+"/actions/?cerrado="+id;
}

function reopen_ticket(id)
{
document.location.href=  "http://"+location.hostname+"/actions/?reab="+id;
}

function reactivate_ticket(id)
{
document.location.href=  "http://"+location.hostname+"/actions/?reac="+id;
}

function wait_ticket(id)
{
document.location.href=  "http://"+location.hostname+"/actions/?en_espera="+id;
}

function claim_ticket(id,curr_user,lastuser)
{
document.location.href=  "http://"+location.hostname+"/actions/?claim="+id+"&curruser="+curr_user+"&lastuser="+lastuser;
}


function transfer_ticket(id,curr_user,newuser)
{
document.location.href=  "http://"+location.hostname+"/actions/?transfer="+id+"&curruser="+curr_user+"&newuser="+newuser;
}
