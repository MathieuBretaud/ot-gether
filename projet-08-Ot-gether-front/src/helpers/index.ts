import { useUserStore } from "@/store/user";
import dayjs from "dayjs";

// Permet de vérifier si le pseudo de l'utilisateur est
// présent dans la liste des participants
export const isPseudoExist = ({ participants, isParticipate }: any) => {
  const userStore = useUserStore();
  if (participants.length > 0) {
    for (let i = 0; i < participants.length; i++) {
      if (participants[i].pseudo === userStore.getUserInfo.pseudo) {
        return (isParticipate.value = true);
      }
    }
  } else {
    return (isParticipate.value = false);
  }
};

// Permet de vérifier si la date de fin d'inscription de l'événement est passée
export const datePassed = ({ eventDate, dateIsPassed }: any) => {
  if (!dayjs().isBefore(eventDate)) {
    dateIsPassed.value = true;
  }
};

export const formatDate = (eventDate: any) => {
  const date = dayjs(eventDate).format("DD/MM/YYYY");
  return date;
};
