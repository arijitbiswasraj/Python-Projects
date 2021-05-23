import os
import time
import pickle

# path = os.path.dirname(os.path.realpath('__file__'))

class FileIndexer:
    def __init__(self):
        #initializing the pickle file and output file name
        self.index_marker_file = "index_mark.pickle"
        self.index_file = "index.pickle"
        self.index_marker_output = "index_marker.txt"
        self.index_output = "index.txt"
        self.index_marker = {}
        self.word_dict = {}
        self.current_file_list = []
        self.avoid_list = []
        self.inverted_word_count = {}
        self.word_count = {}
        
    def create_index(self):
        path = os.getcwd()
        print("***************Starting indexing*****************")
        file_list = self.absoluteFilePaths(path)
        self.current_file_list = file_list
        #fetching the file list from the os
        self.avoid_list.append(path+"\index_mark.pickle")
        self.avoid_list.append(path + "\index.pickle")
        self.avoid_list.append(path + "\index.txt")
        self.avoid_list.append(path + "\index_marker.txt")
        self.avoid_list.append(path + "\source.py")
        #printing the avoid list
        print(self.avoid_list)
        #checking if the indexer has been run anytime or not
        #checking is the makrer file is there if its there it means the indexer has been run previously
        if os.path.exists(self.index_marker_file) :
            with open(self.index_marker_file, "rb") as file:
                self.index_marker = pickle.load(file)
            with open(self.index_file,"rb") as file:
                self.word_dict = pickle.load(file)
            for file in file_list:
                flag = 1
                if file in self.index_marker and file not in self.avoid_list:
                    #print(str(file) + str(self.avoid_list))
                    #print("ok")
                    if os.path.getmtime(file) > self.index_marker[file]:
                        # the file has changed since last index
                        flag = 0
                #if the file is not in marker then reindex it
                elif not (file in self.index_marker )and file not in self.avoid_list:
                    flag = 0
                    # the file is newly added
                if flag == 0:
                    # indexing the file
                    print("Indexing file: " + file)
                    with open(file, encoding="utf8") as fp:
                        lines = fp.readlines()
                        words = []
                        for line in lines:
                            word = line.split()
                            for w in word:
                                words.append(w.lower())
                        for word in words:
                            word = ''.join(e for e in word if e.isalnum())
                            if word not in self.word_dict:
                                self.word_dict[word] = []
                            self.word_dict[word].append(file)

                    self.index_marker[file] = time.time()


        else:
            # indexing for the first time
            for file in file_list :
                if file not in self.avoid_list:
                    print("Indexing file: " + file)
                    with open(file, encoding="utf8") as fp:
                        lines = fp.readlines()
                        words = []
                        for line in lines:
                            word = line.split()
                            for w in word:
                                words.append(w.lower())
                        for word in words:
                            word = ''.join(e for e in word if e.isalnum())
                            if word not in self.word_dict:
                                self.word_dict[word] = []
                            self.word_dict[word].append(file)
                    self.index_marker[file] = time.time()

    def remove_deleted(self):
        # this function deletes the file from index that has been deleted
        delete_list = []
        for file in self.index_marker:
            if file not in self.current_file_list:
                #this is the file we need to delete as well as all the entries
                delete_list.append(file)
        #this is to update the index
        for file in delete_list:
            del(self.index_marker[file])
            word_remove_list = []
            for word in self.word_dict:
                if file in self.word_dict[word]:
                    self.word_dict[word].remove(file)
                if len(self.word_dict[word]) == 0:
                    # this word now refers to no files so remove this word
                    word_remove_list.append(word)
            for word in word_remove_list:
                del(self.word_dict[word])

    def print_index(self):
        for word in self.word_dict:
            print(word + ": "+ str(self.word_dict[word]))

    def write_index(self):
        with open(self.index_output,"w") as file:
            for word in self.word_dict:
                file.write(word + ": " + str(self.word_dict[word]) + '\n')

    def write_marker(self):
        with open(self.index_marker_output,"w") as file:
            for name in self.index_marker_output:
                file.write(name + ": " + self.index_marker_output[name] + "\n")

    def searcher(self):
        word = input("Enter the word you want to search.").lower()
        if word in self.word_dict:
            print("Word found in the follwing files: ")
            for file in self.word_dict[word]:
                print(file)
        else:
            print("Zero occurences found.")

    def commit(self):
        with open(self.index_marker_file, "ab") as file:
            pickle.dump(self.index_marker, file)
        with open(self.index_file, "ab") as file:
            pickle.dump(self.word_dict, file)

    def absoluteFilePaths(self,directory):
        file_list = []
        for dirpath, _, filenames in os.walk(directory):
            for f in filenames:
                file_list.append(os.path.abspath(os.path.join(dirpath, f)))
        return file_list
    def calculateWordCount(self):
        #self.run()
        self.word_count.clear()
        for key,value in self.word_dict.items():
            self.word_count[key] = len(value)
        
    def printWordCount(self):
        self.calculateWordCount()
        print("Word count:\n")
        for (word,count) in self.word_count.items():
            print(word +":"+str(count))

            
    def createInvertedWordCount(self):
        #self.run()
        self.inverted_word_count.clear()
        self.calculateWordCount()
        #print(self.word_count["troops"])
        for (key,value) in self.word_count.items():
            if value in self.inverted_word_count:
                self.inverted_word_count[value].append(key)
            else:
                self.inverted_word_count[value] = []
                self.inverted_word_count[value].append(key)
                #print(value)
        
    def printInvertedWordCount(self):
        self.createInvertedWordCount()
        for frequency in self.inverted_word_count:
            print(str(frequency) + ": "+ str(self.inverted_word_count[frequency]))
            
    def run(self):
        self.create_index()
        self.remove_deleted()
        while True:
            c = int(input("1. Search for a file.\n2. Print the entire index.\n3. Write the entire index to a file.\n4. Write the list of entire indexed files to a file.\n5. Print word count.\n 6.Print inverted word count.\n7. Exit.\n"))
            if c == 1:
                self.searcher()
            elif c==2:
                self.print_index()
            elif c==3:
                self.write_index()
            elif c==4:
                self.write_marker()
            elif c==5:
                self.printWordCount()
            elif c==6:
                self.printInvertedWordCount()
            else:
                self.commit()
                exit()
ob = FileIndexer()
ob.run()



