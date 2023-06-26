<?php
header('Content-Type: text/xml');
echo '<alerts_response operation="send_batch">
<status code="0">Status Message</status>
<message_ok_count>NN</message_ok_count>
<exception_list>
<exception code="03">02199906897</exception>
</exception_list>
</alerts_response>';
