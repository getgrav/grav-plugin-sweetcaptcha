# Sweet Captcha Plugin

The **Sweet Captcha** Plugin is for [Grav CMS](http://github.com/getgrav/grav) that allows the use of a [sweetCaptcha](http://sweetcaptcha.com/) captcha field in your Grav forms.

> IMPORTANT: This plugin requires Grav 1.1+ and Forms 1.3+ plugin

## Installation

Installation can occur via the usual means:

#### GPM Installation

```
$ bin/gpm install sweetcapture
```

#### Admin Installation

Navigate to the **Plugins** via the side menu.  Then click the `+ Add` button.  Then select **SweetCapture** from the available plugins to install.

## Setup

After installing the plugin you need to register for a **Free Account** with on [sweetcaptcha.com](http://sweetcaptcha.com/).  After you have created your account, you should  add a new **URL**, this will prompt you to choose some options or even upgrade to the **premium themes**.  The default free **Fun** theme works just fine however.

At the bottom of this form under **Advanced** topic title, is an option to **Reveal Credentials**.  This information is required to configure the plugin.

If you are working via the CLI, copy the `user/plugins/sweetcaptcha/sweetcaptcha.yaml` file to `user/config/plugins/sweetcaptcha.yaml` and edit the values replacing the defaults with your App credentials:

```
enabled: true
app_id: 500000
app_key: zgt4llnv695fj6drt8bfrdca44m1q64p
app_secret: puoiy9gflz3oqyul04ov8xig85di5hlu
```

If you are using the admin plugin, you can simply enter these values in the **SweetCaptcha** plugin details page.

## Using in a Form

Similar to the built-in [Google Captcha](https://learn.getgrav.org/forms/forms/fields-available#the-captcha-field) field, you need to add the captcha field to your form definition:

You can use the 'old' style:

```
-
    name: sweetcaptcha
    label: SweetCaptcha
    type: sweetcaptcha
    input@: false
```  
          
or the new 'named' style:

```
sweetcaptcha:
    label: SweetCaptcha
    type: sweetcaptcha
    input@: false
```

> Note: The `input@: false` is required to keep the field out of the output sent or saved

The label is totally optional of course.

In order to process and validate the captcha, you must add a `process:` directive at the very top of the list.  It's important that it is at the top to ensure if validation fails, nothing else is processed.

```
process:
    - 
        sweetcaptcha:
    -
        save:
            fileprefix: feedback-
            dateformat: Ymd-His-u
            extension: txt
            body: '{% include ''forms/data.txt.twig'' %}'
    -
        message: 'Thank you from your feedback!'
    -
        display: thankyou
```

