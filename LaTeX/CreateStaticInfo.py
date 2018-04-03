filename = "infotest.tex" #file name

#frequently used commands
check = "\\CheckBox"
uncheck = "\\UnCheckBox"

def newCom(command, value): #new command
	file.write("\\newcommand{" + command + "}{" + value + "}\n")

def ifThen(value): #ifthenelse
	file.write("\\ifthenelse{\\equal{\\AIOChoice}{" + value + "}}{\n")

def tab(): #print two spaces for a tab
	file.write("  ")

def empty(): #print empty curly brackets
	file.write("{}\n")

def nl(): #newline
	file.write("\n")

def allegationDiscussion():
	file.write("\\newcommand{\\AllegationDiscussion}{")
	nl()
	file.write(
		"I believe your explanation that you did not understand some of \n"
		"the assignment instructions and believed that you were in full \n"
		"compliance with Dalhousie academic integrity policy.  I also \n"
		"commend you for forthrightly explaining what had occurred and\n"
		"accepting full responsibility for the errors.   Since the work\n"
		"in Assignment 2 constitutes work that is not uniquely yours, a\n"
		"grade cannot be awarded to this assignment.  Furthermore, I \n"
		"believe that an intellectual property workshop will help answer\n"
		"some of the issues we discussed during our meeting.  As we discussed,\n"
		"ensuring that secondary sources are not in front of you while you are\n"
		"coding your own solution, will help ensure that you avoid a similar\n"
		"issue in the future.  The workshop will hopefully provide additional tips on \n"
		"avoiding plagiarism.  I am hopeful that you will never be in a similar \n"
		"situation in the future.\n")
	file.write("}\n")

def penalties():
	file.write("\\newcommand{\\Penalties}{\n")
	tab()
	file.write("\\begin{itemize}\n")
	file.write("\\vspace*{-0.5ex}\n")
	tab()
	file.write("\\item Receive a grade of 0 on Assignment 42 in CSCI 1234.\n")
	tab()
	file.write("\\vspace*{-0.5ex}\n")
	tab()
	file.write("\\item Attend a workshop on intellectual property.\n")
	tab()
	file.write("\\vspace*{-0.5ex}\n")
	tab()
	file.write("\\end{itemize}\n")
	file.write("}")



file = open(filename, "w") #open file for writing

#if AIO is Alex
newCom("\\AIOChoice", "Alex")
nl()
ifThen("Alex")
tab()
newCom("\\AIO", "Alex Brodsky")
tab()
newCom("\\AIOCC", "AB")
tab()
newCom("\\AIOSig", "alex1.png")
tab()
newCom("\\AIOPhoneExt", "2501")
tab()
newCom("\\AIOEmail", "{\\tt abrodsky@cs.dal.ca}")
file.write("}")
empty()

#if AIO is James
ifThen("James")
tab()
newCom("\\AIO", "James Fleming")
tab()
newCom("\\AIOCC", "JF")
tab()
newCom("\\AIOSig", "james1.png")
tab()
newCom("\\AIOPhoneExt", "????")
tab()
newCom("\\AIOEmail", "{\\tt jfleming@cs.dal.ca}")
file.write("}")
empty()

#if AIO is Christian
ifThen("Christian")
tab()
newCom("\\AIO", "Christian Blouin")
tab()
newCom("\\AIOCC", "CB")
tab()
newCom("\\AIOSig", "christian1.png")
tab()
newCom("\\AIOPhoneExt", "????")
tab()
newCom("\\AIOEmail", "{\\tt cblouin@cs.dal.ca}")
file.write("}")
empty()
nl()

newCom("\\Registrar", "Dr.~Michael McAlister")
newCom("\\RegistrarRecords", "Ms.~Christina Coakley")
newCom("\\AIClerk", "Ms.~Janet Macdonald")
nl()
nl()

newCom("\\Professor", "Morie Artie")
newCom("\\ProfessorEmail", "{\\tt martie@cs.dal.ca}")
newCom("\\Class", "CSCI 1234: Water Computing")
nl()

newCom("\\Student", "Mr.~Joseph Public")
newCom("\\Banner", "B00123456")
newCom("\\Address",
 "1234 Infinite Way, \\\\\nHalifax, Nova Scotia, B3L 4P7")
nl()

newCom("\\Allegation", "committed plagiarism")
newCom("\\Work", "Assignment 42")
newCom("\\MeetDate", "Tuesday 30, February, 2112")
newCom("\\ConfirmDate", "Monday 29, February, 2112")
newCom("\\MeetTime", "12:00 noon ")
newCom("\\MeetLocation",
 "Room \\#211, Computer Science Building, Halifax, NS")
nl()
nl()

#Form B
newCom("\\AllegationDate", "28, February 2112")
newCom("\\AllegationReceivedDate", "28, February 2112")
newCom("\\AllegationLetterDate", "28, February 2112")
newCom("\\Advocate", "")
newCom("\\ProfessorAttend", "NO")
newCom("\\ReasonForDelay", "")
nl()

newCom("\\CommonAllegation", "NO")
newCom("\\NumberOfStudents", "2")
#will have to be turned into a loop based on number of students
newCom("\\CommonStudentNameA", "")
newCom("\\CommonStudentBannerA", "")
newCom("\\CommonStudentNameB", "")
newCom("\\CommonStudentBannerB", "")
newCom("\\CommonStudentNameC", "")
newCom("\\CommonStudentBannerC", "")
nl()

newCom("\\ReasonForNotMeeting", "")
nl()

newCom("\\CheckBox", "\\rule{1em}{1em}")
newCom("\\UnCheckBox", "\\rule[1em]{1em}{0pt}")
newCom("\\TransferReasonA", "\\CheckBox")
newCom("\\TransferReasonB", "\\UnCheckBox")
newCom("\\TransferReasonPrior", "\\UnCheckBox")
newCom("\\TransferReasonMeeting", "\\UnCheckBox")
newCom("\\TransferReasonAssessment", "\\UnCheckBox")
newCom("\\TransferReasonPenalty", "\\UnCheckBox")
newCom("\\TransferReasonCommon", "\\UnCheckBox")
nl()

#Form D
allegationDiscussion()
nl()

penalties()
nl()