<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * Semestreletivo
 *
 * @ORM\Table(name="semestreletivo", indexes={@ORM\Index(name="fk_alocacao_semestreLetivo1_idx", columns={"Semestre"}), @ORM\Index(name="fk_alocacao_vwCurso1_idx", columns={"Curso"})})
 * @ORM\Entity
 */
class Semestreletivo
{
    /**
     * @var integer
     *
     * @ORM\Column(name="Id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \Vwsemestre
     *
     * @ORM\ManyToOne(targetEntity="Vwsemestre")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="Semestre", referencedColumnName="Id")
     * })
     */
    private $semestre;

    /**
     * @var \Vwcurso
     *
     * @ORM\ManyToOne(targetEntity="Vwcurso")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="Curso", referencedColumnName="Id")
     * })
     */
    private $curso;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set semestre
     *
     * @param \Vwsemestre $semestre
     *
     * @return Semestreletivo
     */
    public function setSemestre(\Vwsemestre $semestre = null)
    {
        $this->semestre = $semestre;

        return $this;
    }

    /**
     * Get semestre
     *
     * @return \Vwsemestre
     */
    public function getSemestre()
    {
        return $this->semestre;
    }

    /**
     * Set curso
     *
     * @param \Vwcurso $curso
     *
     * @return Semestreletivo
     */
    public function setCurso(\Vwcurso $curso = null)
    {
        $this->curso = $curso;

        return $this;
    }

    /**
     * Get curso
     *
     * @return \Vwcurso
     */
    public function getCurso()
    {
        return $this->curso;
    }
}

