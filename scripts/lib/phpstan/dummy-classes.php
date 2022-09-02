<?php

// @codingStandardsIgnoreStart
namespace Drupal\webhook_receiver {
  class WebhookReceiver {
    public function webhooks() {
      return [];
    }
  }
  class WebhookReceiverPluginBase {
    public function x() {

    }
  }
}

namespace Drupal\webhook_receiver\SelfTest {
  class RequestResponseTest {
    public function run($x) {}
  }
}

namespace Drupal\webhook_receiver\WebhookReceiverLog {
  interface WebhookReceiverLogInterface {
    public function debug($x);
    public function err($x);
  }
}

namespace Drupal\webhook_receiver\Payload {
  interface PayloadInterface {
    public function toArray();
  }
}

namespace Drupal\webhook_receiver\WebhookReceiverActivityLog {
  interface WebhookReceiverActivityLogInterface {
    public function logThrowable($t);
  }
}
// @codingStandardsIgnoreEnd
