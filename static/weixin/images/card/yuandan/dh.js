function F1(ShowLoader) {
    var UA = navigator.userAgent;
    var IE = -1;
    if (navigator.appName == "Microsoft Internet Explorer") {
        var RE = new RegExp("MSIE ([0-9]{1,}[\.0-9]{0,})");
        if (RE.exec(UA) != null) {
            IE = parseFloat(RegExp.$1);
        }
    }
    var HasTouch = (navigator.userAgent.match(/iPad|iPhone|iPod|Android/i) != null || screen.width <= 600) && ('ontouchstart' in document.documentElement);
    var MouseDown = null,
    MouseUp = null,
    MouseMove = null;
    if (HasTouch) {
        MouseDown = "ontouchstart";
        MouseUp = "ontouchend";
        MouseMove = "ontouchmove";
    } else {
        MouseDown = "onmousedown";
        MouseUp = "onmouseup";
        MouseMove = "onmousemove";
    }
    var MF = "/static/weixin/images/card/yuandan/";
    var B = "";
    B += "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">";
    if (IE > -1 && IE < 9) {
        B += "<html xmlns=\"http://www.w3.org/1999/xhtml\" xmlns:v=\"VML\" >";
    } else {
        B += "<html xmlns=\"http://www.w3.org/1999/xhtml\" xmlns:svg=\"http://www.w3.org/2000/svg\" >";
    }
    B += "<head>";
    B += "<title>Fonts</title>";
    B += "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />";
    if (IE > -1 && IE < 9) {
        B += "<meta http-equiv=\"X-UA-Compatible\" content=\"IE=EmulateIE7\" />";
    }
    B += "<style type=\"text/css\">";
    B += "div,input,textarea{display:none;position:absolute;padding:0;margin:0;}table,svg,img,iframe{table-layout:fixed;display:none;position:absolute;background-color:transparent;-webkit-user-select: none;-webkit-touch-callout: none;}table{table-layout:fixed;}td,form{padding:0;margin:0;}body{overflow:hidden;}";
    if (ShowLoader) {
        B += ".LoaderBorder{width:120px;height:18px; padding:0;font-size:1px;}.LoaderBar{display:block;position:relative;font-size:1px;width:0%;height:100%;margin:0;}.Loader{width:120px;}";
    }
    if (IE > -1 && IE < 9) {
        B += "v\\:*{behavior:url(#default#VML);position:absolute;display:none;}";
    }
    B += "</style>";
    if (IE > -1 && IE < 9) {
        B += "<xml:namespace ns=\"urn:schemas-microsoft-com:vml\" V1=\"v\" />";
    }
    B += "<script type=\"text/javascript\">";
    B += "var V2,V3;";
    var V4 = 0;
    B += "var MF=\"" + MF + "\";";
    B += "var V5;";
    B += "var V6=new Array(172);";
    B += "var V7=new Array(\"Scene1\");";
    B += "var C0,C0L0S8,C0L0S7,C0L0S6,C0L0S5,C0L0S4,C0L0S3,C0L0S2,C0L0S1,C0L0S0;";
    B += "var V8=new Date();";
    B += "var V9=(Math.PI*2)/360,V10=0;";
    B += "var V11,V12=-1,V13=-1,V14=-1,V15=0,V16=0,V17=1,V18=172;";
    B += "var V19=\"none\",V20=\"block\",V21=\"px\";";
    B += "var IE=" + IE + ",V22=0;";
    B += "var V23=true,V24=false;";
    B += "function F2(e){e=F3(e);if(e!=null){if(e.stopPropagation){e.stopPropagation();}if(e.preventDefault){e.preventDefault();}e.cancelBubble = true;e.returnValue=false;}return false;}";
    B += "var V25=null;function F4(e){if(window.event){e=window.event;}if(e.touches){if(e.touches.length==0){return V25;}e=e.touches[0];}var Mouse=new Object();Mouse.X=e.clientX;Mouse.Y=e.clientY;";
    B += "var Scale=Math.min(V26/600,V27/400);";
    B += "Mouse.X=(Mouse.X-(V26-(Scale*600))/2)/Scale;";
    B += "Mouse.Y=(Mouse.Y-(V27-(Scale*400))/2)/Scale;";
    B += "V25=Mouse;return Mouse;}";
    B += "function F3(e){if(window.event){return window.event;}else{return e;}}";
    B += "function F5(Angle){return 90*Math.round(Angle/90);}";
    if (IE > -1 && IE < 9) {
        B += "function AddFilter(Style,NewFilter){";
        B += "var Filter=Style.indexOf(\"filter:\");";
        B += "if(Filter==-1){";
        B += "Style=Style+=\"filter: progid:DXImageTransform.Microsoft.\"+NewFilter+\";\";";
        B += "}else{";
        B += "Style=Style.substring(0,Filter+7)+\" progid:DXImageTransform.Microsoft.\"+NewFilter+Style.substring(Filter+7,Style.length);";
        B += "}";
        B += "var MSFilter=Style.indexOf(\"-ms-filter:'\");";
        B += "if(MSFilter==-1){";
        B += "Style=Style+=\"-ms-filter:'progid:DXImageTransform.Microsoft.\"+NewFilter+\"';\";";
        B += "}else{";
        B += "Style=Style.substring(0,MSFilter+12)+\"progid:DXImageTransform.Microsoft.\"+NewFilter+\", \"+Style.substring(MSFilter+12,Style.length);";
        B += "}";
        B += "return Style;";
        B += "}";
    }
    if (IE == -1 || IE >= 9) {
        B += "var V1=new Array(\"\",\"-webkit-\",\"-moz-\",\"-o-\",\"-ms-\");";
        B += "function F6(Style,NewTransform){";
        B += "for(var i=0;i<5;i++){";
        B += "var Trans=Style.indexOf(V1[i]+\"transform:\");";
        B += "if(Trans==-1){";
        B += "Style=Style+=V1[i]+\"transform: \"+NewTransform+\";\";";
        B += "}else{";
        B += "Style=Style.substring(0,Trans+V1[i].length+10)+\" \"+NewTransform+Style.substring(Trans+V1[i].length+10,Style.length);";
        B += "}";
        B += "}";
        B += "return Style;";
        B += "}";
    }
    B += "var V26=0,V27=0;";
    B += "function F7() {if (parent.document.documentElement.clientWidth > 0){return parent.document.documentElement.clientWidth;}else{return F8(parent.window.innerWidth, parent.document.body.clientWidth);}}";
    B += "function F9() {if (parent.document.documentElement.clientHeight > 0){return parent.document.documentElement.clientHeight;}else{return F8(parent.window.innerHeight, parent.document.body.clientHeight);}}";
    B += "function F8(Value1, Value2) {";
    B += "var Value=0;";
    B += "if (!isNaN(Value1) && Value1 > Value) {Value= Value1;}if (!isNaN(Value2) && Value2 > Value) {Value= Value2;}";
    B += "return Value;";
    B += "}";
    B += "function F10() {V26=F7();V27=F9();if(!V24){V24=V26!=V13||V27!=V14;}V13=V26;V14=V27;}";
    B += "function F11(Id) {return document.getElementById(Id);}";
    B += "function F12(){";
    B += "C0L0S8.src=MF+\"Text_Text1.png\";";
    B += "C0L0S7.src=MF+\"Text_Text2.png\";";
    B += "C0L0S6.src=MF+\"Text_Text3.png\";";
    B += "C0L0S5.src=MF+\"Text_Text4.png\";";
    B += "C0L0S4.src=MF+\"Text_Text5.png\";";
    B += "C0L0S3.src=MF+\"Text_Text6.png\";";
    B += "C0L0S2.src=MF+\"Text_Text7.png\";";
    B += "C0L0S1.src=MF+\"Text_Text8.png\";";
    B += "C0L0S0.src=MF+\"Text_Text9.png\";";
    B += "}";
    B += "function F13(){";
    B += "V2=F11(\"L\");V28=F11(\"LB\");";
    B += "C0=F11(\"C0\");V5=C0;C0L0S8=F11(\"C0L0S8\");C0L0S7=F11(\"C0L0S7\");C0L0S6=F11(\"C0L0S6\");C0L0S5=F11(\"C0L0S5\");C0L0S4=F11(\"C0L0S4\");C0L0S3=F11(\"C0L0S3\");C0L0S2=F11(\"C0L0S2\");C0L0S1=F11(\"C0L0S1\");C0L0S0=F11(\"C0L0S0\");";
    B += "V13=-1;V14=-1;F10();";
    if (ShowLoader) {
        B += "V11=window.setTimeout(\"F14();\",100);";
    } else {
        B += "F12();";
        B += "V8=new Date();";
        B += "V11=window.setTimeout(\"F15();\",5);";
    }
    B += "}";
    B += "function F16(Type,Div,X,Y,Width,Height,Opacity,Angle,CAngle,CRadius,FontSize,BorderWidth){";
    B += "if(Opacity==0){Div.style.display=V19;return;}";
    B += "var Style=\"\";";
    B += "var MX=0,MY=0;";
    B += "var Scale=Math.min(V26/600,V27/400);";
    B += "X*=Scale;Y*=Scale;";
    B += "FontSize*=Scale;BorderWidth*=Scale;Width*=Scale;Height*=Scale;";
    if (IE > -1 && IE < 9) {
        B += "while(Angle>360){Angle-=360;}while(Angle<0){Angle+=360;}";
        B += "if(Type!=1&&Type!=29&&Type!=30&&Type!=33&&Type!=31&&Type!=22&&Type!=7&&Type!=8){";
        B += "Style+=\"rotation:\"+Angle+\";\";";
        B += "if((Type==2||Type==-1)&&Div.firstChild!=null){Div.firstChild.angle=-Angle;}";
        B += "}else{";
        B += "if(Angle!=0){";
        B += "var Quarter=Math.floor(Angle/90);";
        B += "Style=AddFilter(Style,\"BasicImage(rotation=\" + Quarter + \")\");";
        B += "}}";
    } else {
        B += "if(Angle!=0){Style+=\"-webkit-transform-origin:\"+(Width/2)+\"px \"+(Height/2)+\"px;\";Style=F6(Style,\"rotate(\"+Angle.toFixed(2)+\"deg)\");}";
    }
    B += "if(FontSize>0){Style+=\"font-size:\"+FontSize+\"px;\";}";
    B += "if(BorderWidth>0){Style+=\"border-width:\"+Math.round(BorderWidth)+\"px;\";Div.strokeWeight=BorderWidth.toString();}";
    B += "if(Div.style.cssText!=Style){Div.style.cssText=Style;}";
    B += "var NewLeft=(X-(Width/2)+MX),NewTop=(Y-(Height/2)+MY);";
    if (IE > -1 && IE < 9) {
        B += "Div.style.left=Math.round(NewLeft)+V21;";
        B += "Div.style.top=Math.round(NewTop)+V21;";
        B += "Div.style.width=(Math.round(NewLeft+Width)-Math.round(NewLeft))+V21;";
        B += "Div.style.height=(Math.round(NewTop+Height)-Math.round(NewTop))+V21;";
    } else {
        B += "Div.style.left=NewLeft.toFixed(2)+V21;";
        B += "Div.style.top=NewTop.toFixed(2)+V21;";
        B += "Div.style.width=Width.toFixed(2)+V21;";
        B += "Div.style.height=Height.toFixed(2)+V21;";
    }
    if (IE == -1) {
        B += "if(Type==1||Type==29||Type==30||Type==31||Type==22||Type==7||Type==8){";
        B += "Div.style.display=\"table\";";
        B += "}else{";
    }
    B += "Div.style.display=V20;";
    if (IE == -1) {
        B += "}";
    }
    B += "}";
    B += "function F17(Type,Div){";
    B += "Div.style.cssText=\"display:none;\";";
    B += "}";
    B += "function F18(Index){";
    B += "}";
    B += "function F19(){if(V16<V17-1){F18(V16+1);}else{F18(0);}}";
    B += "function F20(Div,Clip){";
    B += "if(Clip){";
    B += "var Scale=Math.min(V26/600,V27/400);";
    B += "Div.style.cssText=\"left:\"+((V26-(Scale*600))/2)+\"px;top:\"+((V27-(Scale*400))/2)+\"px;width:\"+(Scale*600)+\"px;height:\"+(Scale*400)+\"px;display:block;overflow:hidden;\";";
    B += "}else{";
    B += "Div.style.cssText=\"left:0px;top:0px;width:\"+V26+\"px;height:\"+V27+\"px;display:block;overflow:hidden;\";";
    B += "}";
    B += "}";
    if (ShowLoader) {
        B += "function F14(){";
        T = 0;
        V4++;
        B += "F21(MF+\"Text_Text1.png\",true,false);";
        V4++;
        B += "F21(MF+\"Text_Text2.png\",true,false);";
        V4++;
        B += "F21(MF+\"Text_Text3.png\",true,false);";
        V4++;
        B += "F21(MF+\"Text_Text4.png\",true,false);";
        V4++;
        B += "F21(MF+\"Text_Text5.png\",true,false);";
        V4++;
        B += "F21(MF+\"Text_Text6.png\",true,false);";
        V4++;
        B += "F21(MF+\"Text_Text7.png\",true,false);";
        V4++;
        B += "F21(MF+\"Text_Text8.png\",true,false);";
        V4++;
        B += "F21(MF+\"Text_Text9.png\",true,false);";
        B += "V11=window.setTimeout(\"F22();\",15);";
        B += "}";
        B += "function F21(Filename,Wait,BackgroundImage){";
        B += "var I=new Image();";
        B += "if(Wait){I.onload=function(){V22++;};I.onerror=function(){V22++;};}";
        B += "I.src=Filename;";
        B += "}";
        B += "function F22(){";
        if (V4 > 0) {
            B += "var Percent=Math.round(Math.min(100,(V22*100)/" + V4 + "));";
            B += "V28.style.width=Percent+\"%\";";
            B += "V2.style.display=V20;";
            B += "V2.style.left=Math.round((V26-L.clientWidth)/2)+V21;";
            B += "V2.style.top=Math.round((V27-L.clientHeight)/2)+V21;";
        }
        B += "if(V22>=" + V4 + "){";
        B += "V2.style.display=V19;";
        B += "F12();";
        B += "V8=new Date();";
        B += "V11=window.setTimeout(\"F15();\",15);";
        B += "}else{";
        B += "V11=window.setTimeout(\"F22();\",15);";
        B += "}}";
    }
    B += "function F23(){";
    B += "F20(V5,true);";
    B += "V24=false;";
    B += "}";
    B += "function F15(){";
    B += "if(V24){F23();}";
    B += "var CTime=new Date();";
    B += "var Span=CTime.getTime()-V8.getTime();";
    B += "V8=CTime;";
    B += "var SpanGap=Math.min(41,Math.max(1,41-Span+V15));";
    B += "V15=SpanGap;";
    B += "Span=(Span/30)*12;";
    B += "if(Span>1){Span=1;};";
    B += "if(V23){V10+=Span;}";
    B += "if(V10>V18){V10=0;}";
    B += "var Pos=0;";
    B += "switch(V16){";
    B += "case 0:";
    B += "if(V10>=0&&V10<40){Pos=(V10-0)/40;F16(1,C0L0S8,70+(Pos*-27),-66+(Pos*284),119,116,100,268.80+(Pos*91.20),0,0,0,0);}";
    B += "if(V10>=40&&V10<88){F16(1,C0L0S8,43,218,119,116,100,0,0,0,0,0);}";
    B += "if(V10>=88&&V10<140){Pos=(V10-88)/52;F16(1,C0L0S8,43+(Pos*-2),218+(Pos*258),119,116,100,0+(Pos*208.44),0,0,0,0);}";
    B += "if(V10>=140){F16(1,C0L0S8,41,476,119,116,100,208.44,0,0,0,0);}";
    B += "if(V10<4){F17(1,C0L0S7);}";
    B += "if(V10>=4&&V10<44){Pos=(V10-4)/40;F16(1,C0L0S7,150+(Pos*-38),-73+(Pos*257),100,100,100,88.03+(Pos*271.97),0,0,0,0);}";
    B += "if(V10>=44&&V10<92){F16(1,C0L0S7,112,184,100,100,100,0,0,0,0,0);}";
    B += "if(V10>=92&&V10<144){Pos=(V10-92)/52;F16(1,C0L0S7,112+(Pos*-7),184+(Pos*258),100,100,100,0+(Pos*337.25),0,0,0,0);}";
    B += "if(V10>=144){F16(1,C0L0S7,105,442,100,100,100,337.25,0,0,0,0);}";
    B += "if(V10<8){F17(1,C0L0S6);}";
    B += "if(V10>=8&&V10<48){Pos=(V10-8)/40;F16(1,C0L0S6,202+(Pos*-36),-50+(Pos*257),100,100,100,209.65+(Pos*150.35),0,0,0,0);}";
    B += "if(V10>=48&&V10<96){F16(1,C0L0S6,166,207,100,100,100,0,0,0,0,0);}";
    B += "if(V10>=96&&V10<148){Pos=(V10-96)/52;F16(1,C0L0S6,166+(Pos*-13),207+(Pos*257),100,100,100,0+(Pos*281.72),0,0,0,0);}";
    B += "if(V10>=148){F16(1,C0L0S6,153,464,100,100,100,281.72,0,0,0,0);}";
    B += "if(V10<12){F17(1,C0L0S5);}";
    B += "if(V10>=12&&V10<52){Pos=(V10-12)/40;F16(1,C0L0S5,259+(Pos*-37),-64+(Pos*262),100,100,100,47.16+(Pos*312.84),0,0,0,0);}";
    B += "if(V10>=52&&V10<100){F16(1,C0L0S5,222,198,100,100,100,0,0,0,0,0);}";
    B += "if(V10>=100&&V10<152){Pos=(V10-100)/52;F16(1,C0L0S5,222+(Pos*-13),198+(Pos*268),100,100,100,0+(Pos*138.63),0,0,0,0);}";
    B += "if(V10>=152){F16(1,C0L0S5,209,466,100,100,100,138.63,0,0,0,0);}";
    B += "if(V10<16){F17(1,C0L0S4);}";
    B += "if(V10>=16&&V10<56){Pos=(V10-16)/40;F16(1,C0L0S4,366+(Pos*-23),-66+(Pos*265),171,100,100,232.59+(Pos*127.41),0,0,0,0);}";
    B += "if(V10>=56&&V10<104){F16(1,C0L0S4,343,199,171,100,100,0,0,0,0,0);}";
    B += "if(V10>=104&&V10<156){Pos=(V10-104)/52;F16(1,C0L0S4,343+(Pos*-23),199+(Pos*258),171,100,100,0+(Pos*219.37),0,0,0,0);}";
    B += "if(V10>=156){F16(1,C0L0S4,320,457,171,100,100,219.37,0,0,0,0);}";
    B += "if(V10<20){F17(1,C0L0S3);}";
    B += "if(V10>=20&&V10<60){Pos=(V10-20)/40;F16(1,C0L0S3,385+(Pos*3),-43+(Pos*242),100,100,100,115.24+(Pos*244.76),0,0,0,0);}";
    B += "if(V10>=60&&V10<108){F16(1,C0L0S3,388,199,100,100,100,0,0,0,0,0);}";
    B += "if(V10>=108&&V10<160){Pos=(V10-108)/52;F16(1,C0L0S3,388+(Pos*-23),199+(Pos*275),100,100,100,0+(Pos*280.15),0,0,0,0);}";
    B += "if(V10>=160){F16(1,C0L0S3,365,474,100,100,100,280.15,0,0,0,0);}";
    B += "if(V10<24){F17(1,C0L0S2);}";
    B += "if(V10>=24&&V10<64){Pos=(V10-24)/40;F16(1,C0L0S2,448+(Pos*5),-69+(Pos*251),100,100,100,187.25+(Pos*172.75),0,0,0,0);}";
    B += "if(V10>=64&&V10<112){F16(1,C0L0S2,453,182,100,100,100,0,0,0,0,0);}";
    B += "if(V10>=112&&V10<164){Pos=(V10-112)/52;F16(1,C0L0S2,453+(Pos*-19),182+(Pos*264),100,100,100,0+(Pos*113.13),0,0,0,0);}";
    B += "if(V10>=164){F16(1,C0L0S2,434,446,100,100,100,113.13,0,0,0,0);}";
    B += "if(V10<28){F17(1,C0L0S1);}";
    B += "if(V10>=28&&V10<68){Pos=(V10-28)/40;F16(1,C0L0S1,509+(Pos*9),-61+(Pos*247),100,100,100,111.39+(Pos*248.61),0,0,0,0);}";
    B += "if(V10>=68&&V10<116){F16(1,C0L0S1,518,186,100,100,100,0,0,0,0,0);}";
    B += "if(V10>=116&&V10<168){Pos=(V10-116)/52;F16(1,C0L0S1,518+(Pos*-14),186+(Pos*268),100,100+(Pos*-1),100,0+(Pos*224.84),0,0,0,0);}";
    B += "if(V10>=168){F16(1,C0L0S1,504,454,100,99,100,224.84,0,0,0,0);}";
    B += "if(V10<32){F17(1,C0L0S0);}";
    B += "if(V10>=32&&V10<72){Pos=(V10-32)/40;F16(1,C0L0S0,560+(Pos*5),-54+(Pos*240),100,100,100,247.33+(Pos*112.67),0,0,0,0);}";
    B += "if(V10>=72&&V10<120){F16(1,C0L0S0,565,186,100,100,100,0,0,0,0,0);}";
    B += "if(V10>=120&&V10<172){Pos=(V10-120)/52;F16(1,C0L0S0,565+(Pos*-14),186+(Pos*266),100,100,100,0+(Pos*102.06),0,0,0,0);}";
    B += "if(V10>=172){F16(1,C0L0S0,551,452,100,100,100,102.06,0,0,0,0);}";
    B += "break;";
    B += "}";
    B += "var IntTime=parseInt(V10);if(IntTime!=V12){V12=IntTime;";
    B += "}";
    B += "V11=window.setTimeout(\"F15();\",SpanGap);";
    B += "}";
    B += "function F24(X,Y,OX,OY,Angle){X-=OX;Y-=OY;var r=Angle*(Math.PI/180);var ct=Math.cos(r);var st=Math.sin(r);var x=ct*X-st*Y;var y=st*X+ct*Y;var Point=new Object();Point.X=x+OX;Point.Y=y+OY;return Point;}";
    B += "<" + "/script>";
    B += "</head>";
    B += "<body onload=\"window.setTimeout('F13();', 100);\" onresize=\"F10();\" onorientationchange=\"F10();\" ondragstart=\"return false;\" " + MouseDown + "=\"var m=F4(event);\">";
    if (ShowLoader) {
        B += "<table id=\"L\" class=\"Loader\"><tr><td class=\"LoaderBorder\"><div id=\"LB\" class=\"LoaderBar\"></div></td></tr></table>";
    }
    B += "<div id=\"C0\">";
    if (IE > -1 && IE < 9) {
        B += "<v:image id=\"C0L0S8\" ></v:image>";
    } else {
        B += "<img id=\"C0L0S8\" />";
    }
    if (IE > -1 && IE < 9) {
        B += "<v:image id=\"C0L0S7\" ></v:image>";
    } else {
        B += "<img id=\"C0L0S7\" />";
    }
    if (IE > -1 && IE < 9) {
        B += "<v:image id=\"C0L0S6\" ></v:image>";
    } else {
        B += "<img id=\"C0L0S6\" />";
    }
    if (IE > -1 && IE < 9) {
        B += "<v:image id=\"C0L0S5\" ></v:image>";
    } else {
        B += "<img id=\"C0L0S5\" />";
    }
    if (IE > -1 && IE < 9) {
        B += "<v:image id=\"C0L0S4\" ></v:image>";
    } else {
        B += "<img id=\"C0L0S4\" />";
    }
    if (IE > -1 && IE < 9) {
        B += "<v:image id=\"C0L0S3\" ></v:image>";
    } else {
        B += "<img id=\"C0L0S3\" />";
    }
    if (IE > -1 && IE < 9) {
        B += "<v:image id=\"C0L0S2\" ></v:image>";
    } else {
        B += "<img id=\"C0L0S2\" />";
    }
    if (IE > -1 && IE < 9) {
        B += "<v:image id=\"C0L0S1\" ></v:image>";
    } else {
        B += "<img id=\"C0L0S1\" />";
    }
    if (IE > -1 && IE < 9) {
        B += "<v:image id=\"C0L0S0\" ></v:image>";
    } else {
        B += "<img id=\"C0L0S0\" />";
    }
    B += "</div>";
    B += "</body></html>";
    document.body.innerHTML = "<iframe id=\"Movie\" style=\"width:100%;height:300px;\" scrolling=\"no\" frameborder=\"0\"></iframe>";
    var IFrame = document.body.firstChild;
    var Doc = IFrame.contentDocument || IFrame.contentWindow.document;
    Doc.open();
    Doc.write(B);
    Doc.close();
    if (window == window.top) {
        window.onorientationchange = function() {
            document.getElementById("Movie").style.cssText = "width:" + window.innerWidth + "px;height:" + window.innerHeight + "px;";
        }
    }
}