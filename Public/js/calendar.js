var start = false;
			var end = false;

			function startDate() {
				$("#i_am_a_table").css("margin-top", "10px");
				$("#i_am_a_table").css("margin-left", "25px");
				$("#i_am_a_table").css("display", "block");
				start = true;
				end = false;
			}

			function endDate() {
				$("#i_am_a_table").css("margin-top", "10px");
				$("#i_am_a_table").css("margin-left", "160px");
				$("#i_am_a_table").css("display", "block");
				start = false;
				end = true;
			}


			function RunNian(The_Year)
			{
			if ((The_Year%400==0) || ((The_Year%4==0) && (The_Year%100!=0)))
			return true;
			else
			return false;
			}
			function GetWeekday(The_Year,The_Month)
			{

			var Allday;
			Allday = 0;
			if (The_Year>2000)
			{

			for (i=2000 ;i<The_Year; i++) 
			if (RunNian(i)) 
			Allday += 366;
			else
			Allday += 365;
			for (i=2; i<=The_Month; i++)
			{
			switch (i)
			{
			case 2 : 
			if (RunNian(The_Year))
			Allday += 29;
			else
			Allday += 28;
			break;
			case 3 : Allday += 31; break;
			case 4 : Allday += 30; break;
			case 5 : Allday += 31; break;
			case 6 : Allday += 30; break;
			case 7 : Allday += 31; break;
			case 8 : Allday += 31; break;
			case 9 : Allday += 30; break;
			case 10 : Allday += 31; break;
			case 11 : Allday += 30; break;
			case 12 : Allday += 31; break;

			}

			}
			}

			return (Allday+6)%7;


			}

			function chooseday(The_Year,The_Month,The_Day)
			{
			var Firstday;
			var completely_date;
			if (The_Day!=0)
			completely_date = (The_Year+1900) + "-" + The_Month + "-" + The_Day;
			else
			completely_date = "No Choose";
			//showdate 只是一个为了显示而采用的东西，
			//如果外部想引用这里的时间，可以通过使用 completely_date引用完整日期
			//也可以通过The_Year,The_Month,The_Day分别引用年，月，日
			//当进行月份和年份的选择时，认为没有选择完整的日期
			//showdate.innerText = completely_date;
			if(start)
				$("#startDate").val(completely_date);
			if(end)
				$("#endDate").val(completely_date);

			$("#i_am_a_table").css("display", "none");
			Firstday = GetWeekday(The_Year,The_Month);
			ShowCalender(The_Year,The_Month,The_Day,Firstday);

			}

			function nextmonth(The_Year,The_Month)
			{
				if (The_Month==12)
				chooseday(The_Year+1,1,0);
				else
				chooseday(The_Year,The_Month+1,0);
				$("#i_am_a_table").css("display", "block");
			}

			function prevmonth(The_Year,The_Month)
			{
				if (The_Month==1)
				chooseday(The_Year-1,12,0);
				else
				chooseday(The_Year,The_Month-1,0);
				$("#i_am_a_table").css("display", "block");
			}

			function prevyear(The_Year,The_Month)
			{
				chooseday(The_Year-1,The_Month,0);
				$("#i_am_a_table").css("display", "block");
			}

			function nextyear(The_Year,The_Month)
			{
				chooseday(The_Year+1,The_Month,0);
				$("#i_am_a_table").css("display", "block");
			}





			function ShowCalender(The_Year,The_Month,The_Day,Firstday)
			{

			var showstr;
			var Month_Day;
			var ShowMonth;
			var today;
			today = new Date();



			switch (The_Month)
			{
			case 1 : ShowMonth = "January"; Month_Day = 31; break;
			case 2 :
			ShowMonth = "February";
			if (RunNian(The_Year))
			Month_Day = 29;
			else
			Month_Day = 28;
			break;
			case 3 : ShowMonth = "March"; Month_Day = 31; break;
			case 4 : ShowMonth = "April"; Month_Day = 30; break;
			case 5 : ShowMonth = "May"; Month_Day = 31; break;
			case 6 : ShowMonth = "June"; Month_Day = 30; break;
			case 7 : ShowMonth = "July"; Month_Day = 31; break;
			case 8 : ShowMonth = "August"; Month_Day = 31; break;
			case 9 : ShowMonth = "September"; Month_Day = 30; break;
			case 10 : ShowMonth = "October"; Month_Day = 31; break;
			case 11 : ShowMonth = "November"; Month_Day = 30; break;
			case 12 : ShowMonth = "December"; Month_Day = 31; break;

			}


			showstr = "";
			showstr = "<Table cellpadding=0 cellspacing=0 border=1 bordercolor=#999999 width=95% align=center valign=top>"; 
			showstr += "<tr><td width=0 style='cursor:pointer' onclick=prevyear("+The_Year+"," + The_Month + ")><<</td><td width=0> " + The_Year + " </td><td width=0 onclick=nextyear("+The_Year+","+The_Month+") style='cursor:pointer'>>></td><td width=0 style='cursor:pointer' onclick=prevmonth("+The_Year+","+The_Month+")><<</td><td width=100 align=center>" + ShowMonth + "</td><td width=0 onclick=nextmonth("+The_Year+","+The_Month+") style='cursor:pointer'>>></td></tr>";
			showstr += "<tr><td align=center width=100% colspan=6>";
			showstr += "<table cellpadding=0 cellspacing=0 border=1 bordercolor=#999999 width=100%>";
			showstr += "<Tr align=center bgcolor=#999999> ";
			//showstr += "<td><strong><font color=#0000CC>日</font></strong></td>";
			//showstr += "<td><strong><font color=#0000CC>一</font></strong></td>";
			//showstr += "<td><strong><font color=#0000CC>二</font></strong></td>";
			//showstr += "<td><strong><font color=#0000CC>三</font></strong></td>";
			//showstr += "<td><strong><font color=#0000CC>四</font></strong></td>";
			//showstr += "<td><strong><font color=#0000CC>五</font></strong></td>";
			//showstr += "<td><strong><font color=#0000CC>六</font></strong></td>";
			showstr += "</Tr><tr>";

			for (i=1; i<=Firstday; i++)
			showstr += "<Td align=center bgcolor=#CCCCCC> </Td>";

			for (i=1; i<=Month_Day; i++)
			{
			if ((The_Year==today.getYear()) && (The_Month==today.getMonth()+1) && (i==today.getDate()))
			bgColor = "#FFCCCC";
			else
			bgColor = "#CCCCCC";

			if (The_Day==i) bgColor = "#FFFFCC";
			showstr += "<td align=center bgcolor=" + bgColor + " style='cursor:pointer' onclick=chooseday(" + The_Year + "," + The_Month + "," + i + ")>" + i + "</td>";
			Firstday = (Firstday + 1)%7;
			if ((Firstday==0) && (i!=Month_Day)) showstr += "</tr><tr>";
			}
			if (Firstday!=0) 
			{
			for (i=Firstday; i<7; i++) 
			showstr += "<td align=center bgcolor=#CCCCCC> </td>";
			showstr += "</tr>";
			}

			showstr += "</tr></table></td></tr></table>";
			cc.innerHTML = showstr; 


		}