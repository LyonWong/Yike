<?php
router::attr('page', 'w|v|m|lesson|study|blog|user|my|teacher|promote|article|create|home|tool|detail');
router::rewrite('/{page}/.*', '/entry');
