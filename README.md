# EXT:calendarize_news

## Configuration

Please set the news default PID "plugin.tx_news.settings.defaultDetailPid" or set the Detail page ID in the calendarize
Plugin configuration!

## Routing configuration example

Based on this issue https://github.com/lochmueller/calendarize_news/issues/30

    NewsDetail:
      type: Extbase
      extension: News
      plugin: Pi1
      routes:
        -
          routePath: '/{news_title}'
          _controller: 'News::detail'
          _arguments:
            news_title: news
        -
          routePath: '/{news_title}-{index}'
          _controller: 'News::detail'
          _arguments:
            news_title: news
            calendarize_index: index
      defaultController: 'News::detail'
      aspects:
        news_title:
          type: PersistedPatternMapper
          tableName: tx_news_domain_model_news
          routeFieldPattern: '^(?P<path_segment>.+)-(?P<uid>\d+)$'
          routeFieldResult: '{path_segment}-{uid}'
        calendarize_index:
          type: PersistedAliasMapper
          tableName: tx_calendarize_domain_model_index
          routeFieldName: uid

## Index based news list

Enable `replaceNewsRepositoryByIndexSelection` in the extension configuration and set the ordering of the news list
plugin to `calendarize` (add `calendarize` to `plugin.tx_news.settings.orderByAllowed`). The news list then contains
one item per upcoming calendarize index, ordered by date (including pagination). Links in the list contain the index,
so the detail view shows the date of the selected index.
