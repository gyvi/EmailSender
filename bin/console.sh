#!/bin/bash
SCRIPT_DIR="$( cd -P "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
if [ "$1" == "-h" ] || [ "$1" == "--help" ] || [ -z "$1" ]; then
  echo "console usage"
  echo "----------------------------"
  echo "queueConsumer: Run emailQueueReceiver.php to consume queue messages."
  echo
  exit
fi

if [ "$1" == "queueConsumer" ]; then
    echo "Starting queueConsumer..."
    php "$SCRIPT_DIR/../src/EmailSender/EmailQueue/Application/Command/emailQueueReceiver.php"
fi