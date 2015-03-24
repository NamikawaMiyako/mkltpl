<?php
  /* MKL TEMPLATE SYSTEM
  著作権的なもの
    MediaKissLab @ Miyako
　　プログラム使用に関する責任
　　　このプログラムを使用する際の全ての危険、責任等はプログラムの使用者が負うものとします。
　　　プログラムを使用した事によるあらゆる損害は、一次的損害、二次的損害を含み
      作者、およびその所属する会社、国家、いかなる団体に関してもこれを負わないものとします。
　　　作者が損害を発生する事を予測できた場合、予測していた場合についても
      同様にあらゆる責任を負わないものとします。
　　　これらの全ての責任はプログラムの使用者が負わなくてはなりません。
　　　プログラムの使用者が責任を負う事ができない場合、このプログラムを使用してはいけません。

      バージョン　2013年 9月25日 バージョン 1.0

    このクレジットは削除できません

    使い方
      テンプレートを読込む
        $DATA         =       @file("テンプレートファイル");

      インスタンスを作成（左辺は何でもOK）
        $TPLSYSTEM    =       new template();

      変数にテンプレートを展開する
        $HTML         =       $TPLSYSTEM->set($DATA,'インデックス');

      HTMLデータをテンプレートに埋め込む
        $HTMLDATA     =       $TPLSYSTEM->htmlset($HTML['インデックス'],'%ラベル%','差込データ','繰返し');
        　　・繰返し　0...繰返さない
                      1...繰返し可能

      HTMLを表示
        $TPLSYSTEM->htmlout($HTMLDATA,フラグ);
            ・フラグが0だと画面出力/1だと変数に代入する

      特殊文字を HTML エンティティに変換する
        $TPLSYSTEM->hsc($_GET['ukeke']);
        $TPLSYSTEM->hsc($_POST['ukeke']);
        $TPLSYSTEM->hsc($_SESSION['ukeke']);
  */

  // Time Zoneのエラー対応
  date_default_timezone_set('Asia/Tokyo');


  class template {
    // テンプレートクラス
    function set($TPLFILE,$TPLNAME) {
      $HTMLDATA                         =       array();
      $LOOPMARK                         =       "";
      $COUNT                            =       0;
      $TPL                              =       array();
      $TPLCNT                           =       0;

      $TEMPLATE                         =       @file($TPLFILE);

      if ( !empty($TEMPLATE) ) {
        if ( !empty($TPLNAME) ) {
          $LOOPMARK                     =       $TPLNAME;
        } else {
          $LOOPMARK                     =       "tplmain";
        }

        # 指定されたテンプレートをLABEL(Begin～Endを一区切りとして配列に分ける）
        foreach ( $TEMPLATE as $VAL ) {
          if ( preg_match('/<!-- Begin/', $VAL) ) {
            $TMP                        =       preg_split('/[ ]+/', $VAL);
            // 1つ前のテンプレートラベルを保存
            $TPL[$TPLCNT]               =       $LOOPMARK;
            $TPLCNT++;

            // 1つ前のテンプレート内にターゲットを保存
            if ( !empty($HTMLDATA[$LOOPMARK]) ) {
              $HTMLDATA[$LOOPMARK]      .=      '%'."$TMP[3]".'%'."\n";
            } else {
              $HTMLDATA[$LOOPMARK]      =    '%'."$TMP[3]".'%'."\n";
            }

            $LOOPMARK                   =       $TMP[3];
            $HTMLDATA[$LOOPMARK]        =       "";
          } else if ( preg_match('/<!-- End/', $VAL) ) {
            $TMP      =       preg_split('/[ ]+/', $VAL);
            if ( $LOOPMARK === $TMP[3] ) {
              $TPLCNT--;
              $LOOPMARK                 =       $TPL[$TPLCNT];
              $COUNT++;
            }
          } else {
            if ( !empty($HTMLDATA[$LOOPMARK]) ) {
              $HTMLDATA[$LOOPMARK]      .=     $VAL;
            } else {
              $HTMLDATA[$LOOPMARK]      =      $VAL;
            }
          }
        }
      } else {
        # テンプレートが読込まれていない場合のエラーメッセージ
        print "テンプレートファイルが読込まれていません！";
      }

      return $HTMLDATA;
    }

    function htmlout($HTMLDATA,$PR="0") {
      // 余計なLABELを削除。この場合はstr_replaceじゃなくてpreg_replaceじゃないとダメなのね。。。
      $tmpdata                          =       preg_replace('/%[a-z0-9A-Z-_]+%/','',$HTMLDATA);
      $OUTPUT                           =       preg_replace('/(\r\n){2,}|\r{2,}|\n{2,}/',"\n",$tmpdata);

      if ( $PR  != "0" ) {
        // $PRが0以外の場合は、HTMLを呼び出し元に返す
        return $OUTPUT;
      } else {
        // HTMLを出力する
        print $OUTPUT;
      }
    }

    function htmlset($HTMLDATA,$LABEL,$REPDATA,$LP) {
      if ( $LP == '0' ) {
        return str_replace($LABEL,$REPDATA,$HTMLDATA);
      } else {
        # テンプレートに指定されたLABELと実データを置換。繰返し用に同じラベルを追加する
        return str_replace($LABEL,$REPDATA.$LABEL,$HTMLDATA);
      }
    }

    function hsc($STR) {
      // POSTやGETで受け取った特殊文字を HTML エンティティに変換する
      if (is_array($STR)) {
        return array_map("hsc",$STR);
      } else {
        return htmlspecialchars($STR,ENT_QUOTES);
      }
    }
  }
?>
