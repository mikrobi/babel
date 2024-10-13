## SEO Friendly Multilingual Websites with MODx and Babel

!!! caution "This is old!"

    This tutorial is a slightly modernized version of the [old blog
    entry](https://web.archive.org/web/20180927220103/http://www.multilingual-modx.com/blog/2011/seo-friendly-multilingual-websites-with-modx-and-babel.html)
    on multilingual-modx.com which is available only in the WaybackMachine. It uses
    a routing plugin for easier maintaining the contexts. The links in the
    text are updated to actual or to WaybackMachine versions.

In my [previous article](tutorial.md) about setting up multilingual websites
with MODx and Babel I described a solution which is based on different
(sub)domains for each language. This domain based approach is implemented easily
but has some drawbacks in a SEO point of view: By using different domains for
each language you automatically split up your site into several single sites.
Each site will be handled separately by search engines. For example, they won't
share the same page rank and backlinks. Using one domain and subfolders for each
language may improve your site's overall ranking: All backlinks are connected to
your top level domain. In this article I'll describe a possible solution of how
to set up a multilingual website with MODx and Babel by using one domain and
subfolders for each language.

This article doesn't focus on the SEO point of view. It's rather a technical
tutorial of how to set up a multilingual website by using subfolders. If you'd
like to read more about the "(sub)domains vs. subfolders" topic you may search
the web (there are a lot of articles about this topic) or read some of the
following posts of other blogs:

- [Subfolders v/s Subdomains: Which one to choose for SEO?](https://web.archive.org/web/20150810025806/http://seohawk.com/subfolders-subdomains-seo)
- [Subdomains or Subfolders : Which are Better for SEO?](https://www.searchenginejournal.com/subdomains-vs-subfolders-seo/239795/)
- [Subdomains, Subfolders and Top-Level Domains](https://www.mattcutts.com/blog/subdomains-and-subdirectories/)
- [Subdomains and subdirectories](https://moz.com/blog/subdomains-subfolders-and-toplevel-domains)

### Technical Background

I'll describe the procedure of setting up the multilingual site by providing a
fictional example site _https://​www.​example.com_. The main website is reachable via
_https://​www.​example.com_ and is available in two languages:

- German: _https://​www.​example.com/de/_
- English: _http://​www.​example.com/en/_

For each language we are using one context: _web_ for German, _en_ for English.

### Prerequisites

Before starting with this tutorial you should be sure that **all** requirements
for a multilingual site are satisfied:

- Friendly URLs are enabled: friendly_urls and use_alias_path are set to yes (1)
- The Apache rewrite engine is activated and the rewrite base is set correctly:

    ```
    RewriteEngine On
    RewriteBase /
    ```

- If you're running your site in a non-root directory like /subfolder/mysite/xy you have to define your rewrite base like this:

    ```
    RewriteBase /subfolder/mysite/xy/
    ```

- The base URL is set via the <base> Tag in your HTML head of all your templates:

    ```
    <head>
        ...
        <base href="[[!++site_url]]" />
        ...
    </head>
    ```

### Step-by-Step Instructions

You have to follow the five steps described in my previous article about setting
up multilingual websites and one additional step:

1. Create your contexts for each language: no differences to domain based approach.
2. Configure language specific settings of all your contexts: `site_url`, `cultureKey` **and** `base_url`.<br>

    | context | site_url                      | cultureKey | base_url |
    |---------|-------------------------------|------------|----------|
    | web     | https://​www.​example.com/de/ | de         | /de/     |
    | en      | https://​www.​example.com/en/ | en         | /en/     |

    !!! caution "Differences to the non SEO version"

        Instead of using different domains in the `site_url` context setting you have to
        use subfolders and additionally specifiy the `base_url` according to the context's
        `cultureKey`.

    !!! hint
    
        You should also define settings like `site_start` `error_page` etc. for each of
        your contexts. To maintain this entries a lot easier you can use the MODX Extra
        [CrossContextsSettings](https://extras.modx.com/package/crosscontextssettings).

3. Grant the _"Load Only"_ access policy for all your contexts to the _anonymous_
   group: no differences to domain based approach.
4. Install a routing extra from the MODX repository, i.e. SmartRouting, xRouting
   or LangRouter via the MODX package management
5. Install the Babel Extra via package management: no differences to domain based approach.
6. Change existing rewrite rules for friendly URLs and add additional rules to your .htaccess file (see next section for detailed description):

    ```
    # redirect all requests to /en/favicon.ico and /de/favicon.ico
    # to /favicon.ico
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^(en|de)/favicon.ico$ favicon.ico [L,QSA]
    
    # redirect all requests to /en/assets* and /de/assets* to /assets*
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^(en|de)/assets(.*)$ assets$2 [L,QSA]
    ```

### Rewrite Rules

If you don't add the rewrite rules above to your .htaccess file the pages are
still accessible via the language subfolders and linking your pages with relative
links should work.

But there would be  a problem regarding relative links: linking assets like CSS,
JavaScripts, images etc. won't work properly. Normally all these files are
located somewhere in the assets subfolder of your MODx root directory. When
including an asset via a relative URL like _assets/css/style.css_ the asset won't
be found:

1. The browser will try to request something like
   _https://​www.​example.com/en/assets/css/style.css_ because the site's URL
   _https://​www.​example.com/en/_ (defined via the `site_url` context setting in step
   2) is used to handle relative URLs.
2. The rewrite rule from above will be applied and the request will be 
   internally forwarded to
   _https://​www.​example.com/index.php?cultureKey=en&q=assets/css/style.css_
3. MODx won't find any resource matching the alias _assets/css/style.css_ and will
   return a 404 error code.

To solve this problem you have to add another rewrite rule before
the rule from above which internally redirects all request to _/[ck]/assets/*_ to
_/assets/*_ where _[ck]_ is a valid culture key:

```
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^(en|de)/assets(.*)$ assets$2 [L,QSA]
```

Fine! Now you can use relative links for your pages and assets. Including images
with TinyMCE should work, too.

You may want to add some additional rewrite rules for other files which are
being referred via relative URLs. For example the _favicon.ico_ in your root
directory:

```
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^(en|de)/favicon.ico$ favicon.ico [L,QSA]
```

!!! hint "Other languages"

    If you're using other languages than German and English you have to change the
    _(en|de)_ part of the rewrite rule according to your needs. For example for a
    website available in English, Spanish and French you can use the following favicon
    rewrite rule:

    ```
    RewriteRule ^(en|es|fr)/favicon.ico$ favicon.ico [L,QSA]
    ```

Additionally, the server can automatically determine the language when the domain
root _https://​www​.example.com/_ is requested and perform a redirect to the
suitable language version in the following way:

- When the accepted language is not German _(de)_ and the root has been
  requested (relative request URI is empty) redirect to the English version
  _(en)_: see first condition and rewrite rule from below (line 2 and 3).
- Otherwise, redirect to German version _(de)_ when the root has been requested:
  see second rewrite rule from below (line 4).

```
# detect language when requesting the root (/)
RewriteCond %{HTTP:Accept-Language} !^de [NC]
RewriteRule ^$ en/ [R=301,L]
RewriteRule ^$ de/ [R=301,L]
```

!!! hint "Only rudimentary rules"

    That's very rudimentary. The condition only checks whether the value of
    the _Accept-Language_ HTTP header variable begins with the language (culture) key.
    But this variable contains much more than only a language key: It's a list of
    preferred (or even non-preferred) keys like this: _Accept-Language:
    de-de,de;q=0.8,en-us;q=0.5,en;q=0.3_. The _q_ variable specifies the importance of
    the language from 0 to 1. Detecting the language with PHP in the gateway plugin
    is much better. But this is not the topic of this article and will be discussed
    in another post.

Ok now all rules and conditions can be added to your _.htaccess_ file. It's **very
important** to place them in the right order before `# The Friendly URLs part`
because Apache goes through the rules from top to bottom:

```
# detect language when requesting the root (/)
RewriteCond %{HTTP:Accept-Language} !^de [NC]
RewriteRule ^$ en/ [R=301,L]
RewriteRule ^$ de/ [R=301,L]

# redirect all requests to /en/favicon.ico and /de/favicon.ico
# to /favicon.ico
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^(en|de)/favicon.ico$ favicon.ico [L,QSA]

# redirect all requests to /en/assets* and /de/assets* to /assets*
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^(en|de)/assets(.*)$ assets$2 [L,QSA]
```

If you'd like to go deeper into defining rewrite rules you should read more
about the [Apache mod_rewrite module](https://httpd.apache.org/docs/2.2/mod/mod_rewrite.html).

### Is this approach optimal?

This solution works fine and editors can work as they did before without caring about relative links and subfolders. But I think this approach is rather a workaround than an optimal solution:

- When linking assets relatively you link to non-existing "virtual" files.
- By applying the rewrite rules for the assets the same file is served via
  several different URLs: _https://​www.​example.com/assets/css/style.css_,
  _https://​www.​example.com/de/assets/css/style.css_ and
  _https://​www.​example.com/en/assets/css/style.css_ return the same content.
- Files which are used in all language versions are not cached for the whole
  site by your browser: The browser doesn't know that
  _https://​www.​example.com/de/assets/css/style.css_ and
  _https://​www.​example.com/en/assets/css/style.css_ are the same.
- When working with the GoogleSiteMap Extra you won't be able to serve a
  sitemap.xml for your whole site without modifying the Extra manually. This is
  because your documents are distributed over several contexts and GoogleSiteMap
  is only capable of creating a sitemap for one context. XML sitemaps are very
  helpful to tell a search engine bot where to find all your pages. So you
  should use them, and they should list all pages of your site!

!!! caution "This is outdated"

    The following section is outdated and will not take place in this form.

### The optimal approach: Babel 2.3!

In my opinion the "cleanest" way to manage a multilingual website would be using
one single context for the whole site and placing your documents into resource
containers for each language. At the moment Babel doesn't support this approach.
But I have already developed a concept of how to change and extend Babel to be
able to use the Extra for both approaches: domain based and subfolder based.

Therefore, I'll introduce an additional level of abstraction into the Babel's
architecture which will make it possible to run Babel in domain-based or
subfolder-based mode. The look and feel will remain the same and the new version
should be compatible with older ones.

I'll need to change a lot of code, and currently I'm working on some other
(paid) projects, too.

I hope you like my plans and I appreciate your feedback!
