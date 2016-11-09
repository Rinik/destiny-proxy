# destiny-proxy

Simple Bungie.net proxy to handle api requests.
You must have Bungie.net API Key to use this proxy.

## how to use proxy?

send request to `http://localhost/proxy/`

`http://localhost/proxy/?clan=`
from clan name returns Clan id

`http://localhost/proxy/?members=`
from clan id returns clan members (member id) in array

`http://localhost/proxy/?member=`
from member id returns member json

`http://localhost/proxy/?full=`
from clan name returns clan member jsons in array

`http://localhost/proxy/?crucible=`
from user name (playstation display name) returns crucible score from destinytracker
