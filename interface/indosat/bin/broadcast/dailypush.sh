#!/bin/bash

#for i in $(seq 0 9);
#do
# ###nohup php  /app/xmp2012/interface/proxl/bin/broadcast/broadcast_queue_sb.php -s pushgame -t $i -n 2000 &
#done

for i in $(seq 0 9);
do
 	nohup php  /app/xmp2012/interface/indosat/bin/broadcast/broadcast_queue_sb.php -s dangdut -t $i -n 100 &
done

#for i in $(seq 0 9);
#do
#        nohup php  /app/xmp2012/interface/indosat/bin/broadcast/broadcast_queue_sb.php -s puasa -t $i -n 100 &
#done

for i in $(seq 0 9);
do
        nohup php  /app/xmp2012/interface/indosat/bin/broadcast/broadcast_queue_sb.php -s musik -t $i -n 100 &
done

for i in $(seq 0 9);
do
        nohup php  /app/xmp2012/interface/indosat/bin/broadcast/broadcast_queue_sb.php -s dg -t $i -n 100 &
done

for i in $(seq 0 9);
do
        nohup php  /app/xmp2012/interface/indosat/bin/broadcast/broadcast_queue_sb.php -s game -t $i -n 100 &
done

for i in $(seq 0 9);
do
        nohup php  /app/xmp2012/interface/indosat/bin/broadcast/broadcast_queue_sb.php -s marijoged -t $i -n 100 &
done

for i in $(seq 0 9);
do
        nohup php  /app/xmp2012/interface/indosat/bin/broadcast/broadcast_queue_sb.php -s gosik -t $i -n 100 &
done

for i in $(seq 0 9);
do
        nohup php  /app/xmp2012/interface/indosat/bin/broadcast/broadcast_queue_sb.php -s asik -t $i -n 100 &
done

