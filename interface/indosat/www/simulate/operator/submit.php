<?php
header('Content-Type: text/xml');
echo '<alerts_response operation="include_list|exclude_list">
<status code="code">Status Message</status>
<affected_rows>1</affected_rows>
<exception_list>
<exception code="fail">021999068910</exception>
<exception code="error">021999068999</exception>
<exception code="errno">021999068998</exception>
</exception_list>
</alerts_response>';
