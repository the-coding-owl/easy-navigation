# easy_navigation
An extension that makes it easy to flag a TYPO3 page record as an entry point of a menu

## Installation
There are two ways of installing this extension either via composer or by the TYPO3 extension manager.
If you are using composer and have a composer project already set up, you can simply do
```
composer require the-coding-owl/easy-navigation
```
to integrate this package into your project.

Since version 1.1.2 this extension is also available on https://extensions.typo3.org and can be downloaded from the TER or from the TYPO3 backend.

## Requirements
```
PHP7.1
TYPO3 9.5
```
## Usage
Once installed, you need to add the static TypoScript template of the extension to your template. It provides you with 5 different DataProcessor configurations for Main-, Meta-, Footer-, Language- and Breadcrumbnavigation. You can use these Dataprocessors by adding them to your `FLUIDTEMPLATE` like this:
```
page = PAGE
page{
  10 = FLUIDTEMPLATE
  10{
    ...
    dataProcessors{
      # Main Navigation
      10 < plugin.tx_easynavigation.main
      # Meta Navigation
      20 < plugin.tx_easynavigation.meta
      # Footer Navigation
      30 < plugin.tx_easynavigation.footer
      # Language Navigation
      40 < plugin.tx_easynavigation.language
      # Breadcrumb Navigation
      50 < plugin.tx_easynavigation.breadcrumb
    }
  }
}
```
In the fluid templates, the navigations are available as `{mainNavigation}`, `{metaNavigation}`, `{footerNavigation}`, `{languageNavigation}` and `{breadcrumbNavigation}`.
As an example you can build up your main navigation like this:
```
<nav class="page-navigation-main">
  <ul class="navigation">
    <f:for each="{mainNavigation}" as="page">
      <li class="item{f:if(condition:'{page.active}',then:' active')}{f:if(condition:'{page.current}',then:' current')}{f:if(condition:'{page.class}',then:' {page.class}')}">
        <a href="{page.link}" target="{page.target}" title="{page.title}">
          <span>{page.title}</span>
        </a>
      </li>
    </f:for>
  </ul>
</nav>
```
To determine where the main navigation begins, you need to add a page-record of type `Main Navigation` into your pagetree and build up your menu as sub-pages of this page. You can add as many pages as you like. The default level of depth for the main navigation is 2, for every other navigation 1. You can change the depth by changing the TypoScript constant `plugin.tx_easynavigation.settings.main.levels`, have a look into the `constants.typoscript` for more information on how to configure the navigation menus.
The breadcrumb navigation generates a trail of links of the pages that are located up in the pagetree as a sort of "back navigation". It can only be configured to exclude certain pages from being included in the navigation.
The language navigation is generated from the `sys_language`-records, located on the root page of the TYPO3 instance (pid 0). It takes the records and generates links to the current page in the fitting language. A fluid template of this navigation could look like this:
```
<nav class="page-navigation-language">
  <ul class="navigation">
    <f:for each="{languageNavigation}" as="language">
      <f:for each="{language.sysLanguages}" as="sysLanguage">
        <f:if condition="{language.languageUid}=={sysLanguage.data.uid}">
          <li class="item language-{sysLanguage.data.language_isocode}{f:if(condition:'{language.active}',then:' active')}">
            <a href="{page.link}" target="{page.target}" title="{sysLanguage.data.title}">
              <span class="flag-{sysLanguage.data.flag}">
                <f:render partial="Navigation" section="languageFlag" arguments="{flag: sysLanguage.data.flag, flagWidth: 50}" />
              </span>
              <span>{sysLanguage.data.title}</span>
            </a>
          </li>
        </f:if>
      </f:for>
    </f:for>
  </ul>
</nav>
```
For further information you can take a look into the `setup.typoscript` where the navigation configurations take place. There is as little PHP code as possible used to generate those navigations to have them very flexible and adaptable. You can change the TypoScript part of the navigation generation at every time.

Feel free to use this extension but be aware that there is no guarantee that it will work out in every possible environment.
# Issues
I highly appreciate it, if you find bugs, have feature request or other ideas regarding this piece of software. Please open an issue ticket here on github if you want to participate.
