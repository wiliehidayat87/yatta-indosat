#!/bin/bash

T="$(date +%s%N)"
NOW_DATE="$(date +%F)"
NOW_TIME="$(date +%H:%M:%S)"
URL="http://localhost:8013/mo/index.php"


c=1
iteration=10
while [ $c -le $iteration ]
do
	RANDOMNUMBER=$((RANDOM%999999999+100000000))
	PARAM='<message type="mo"><adn>9879</adn><msisdn>'$RANDOMNUMBER'</msisdn><tid>'$RANDOMNUMBER'</tid><sms>REG BONUS</sms><tdate>'$NOW_DATE' '$NOW_TIME'</tdate></message>'
	curl -d "$PARAM" $URL
	#echo $URL $PARAM
	(( c++ ))
done

# Time interval in nanoseconds
T="$(($(date +%s%N)-T))"
# Seconds
S="$((T/1000000000))"
# Milliseconds
M="$((T%1000000000/1000000))"

printf "Process Time: %02d:%02d:%02d:%02d.%03d\n" "$((S/86400))" "$((S/3600%24))" "$((S/60%60))" "$((S%60))" "${M}"
