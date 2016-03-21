# SilverStripe Slack Logging

Publish SilverStripe errors and warnings into a Slack channel via an incoming webhook.

## Usage

Use Slack's 'Incoming WebHooks' app to add a webhook to post into the channel of your choice. Copy the URL that Slack generates and add to your config:

```yaml
SS_LogSlackWriter:
  webhook: '{your-webhook-url'
```
